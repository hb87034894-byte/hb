<?php
/**
 * E2L 보험센터 - 견적/가입 신청 API
 * Mailer 클래스를 사용하여 이메일 발송
 */

// 에러를 JSON으로 반환하도록 설정
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // JSON 응답 헤더
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    // OPTIONS 요청 처리
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        echo json_encode(['ok' => true]);
        exit;
    }

    // POST만 허용
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['ok' => false, 'error' => 'POST 요청만 허용됩니다.']);
        exit;
    }

    // Mailer 클래스 로드
    require_once __DIR__ . '/../lib/mailer/Mailer.php';

    // 설정
    $config = array(
        'smtp_email'    => 'simg_admin@simg.kr',
        'smtp_password' => 'simg716673!',
        'to_email'      => 'newbiz@simg.kr',
        'upload_dir'    => __DIR__ . '/../uploads/',
    );

    // 데이터 수집
    $formType = isset($_POST['formType']) ? $_POST['formType'] : 'unknown';
    $data = $_POST;

    // 업로드 폴더 생성
    if (!is_dir($config['upload_dir'])) {
        @mkdir($config['upload_dir'], 0755, true);
    }

    // 파일 업로드 처리
    $attachments = array();
    $uploadedFiles = array();
    
    if (!empty($_FILES)) {
        foreach ($_FILES as $fieldName => $file) {
            if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK && $file['size'] > 0) {
                $originalName = $file['name'];
                $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                $uploadPath = $config['upload_dir'] . $safeName;
                
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $attachments[] = array(
                        'path' => $uploadPath,
                        'name' => $originalName
                    );
                    $uploadedFiles[] = $originalName;
                }
            }
        }
    }

    // 제목 설정
    $subjectMap = array(
        'domestic-quote' => '[E2L] 국내 행사보험 견적 신청',
        'domestic-apply' => '[E2L] 국내 행사보험 가입 신청',
        'global-quote'   => '[E2L] 해외 행사보험 견적 신청',
        'geunjae-quote'  => '[E2L] 근재보험 견적 신청',
    );
    
    $companyField = '';
    if (isset($data['d-company'])) $companyField = $data['d-company'];
    elseif (isset($data['da-company'])) $companyField = $data['da-company'];
    elseif (isset($data['g-company'])) $companyField = $data['g-company'];
    elseif (isset($data['gj-company'])) $companyField = $data['gj-company'];
    
    $subject = isset($subjectMap[$formType]) ? $subjectMap[$formType] : '[E2L] 보험 문의';
    if ($companyField) {
        $subject .= ' - ' . $companyField;
    }

    // 이메일 본문 생성 (HTML)
    $now = date('Y-m-d H:i:s');
    $htmlBody = "<!DOCTYPE html><html><head><meta charset='UTF-8'></head><body>";
    $htmlBody .= "<h2>E2L 보험센터 신청 접수</h2>";
    $htmlBody .= "<p><strong>접수일시:</strong> {$now}</p>";
    $htmlBody .= "<p><strong>신청유형:</strong> {$formType}</p>";
    $htmlBody .= "<hr><h3>입력 정보</h3><table border='1' cellpadding='5' cellspacing='0'>";
    
    foreach ($data as $key => $value) {
        if ($key === 'formType' || strpos($key, 'consent') !== false || strpos($key, '-all') !== false) continue;
        if ($value === '') continue;
        $htmlBody .= "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
    }
    
    $htmlBody .= "</table>";
    
    if (!empty($uploadedFiles)) {
        $htmlBody .= "<hr><h3>첨부파일</h3><ul>";
        foreach ($uploadedFiles as $fileName) {
            $htmlBody .= "<li>" . htmlspecialchars($fileName) . "</li>";
        }
        $htmlBody .= "</ul>";
    }
    
    $htmlBody .= "</body></html>";

    // 텍스트 본문
    $textBody = "=== E2L 보험센터 신청 접수 ===\n\n";
    $textBody .= "접수일시: {$now}\n";
    $textBody .= "신청유형: {$formType}\n\n";
    $textBody .= "--- 입력 정보 ---\n";
    
    foreach ($data as $key => $value) {
        if ($key === 'formType' || strpos($key, 'consent') !== false || strpos($key, '-all') !== false) continue;
        if ($value === '') continue;
        $textBody .= "{$key}: {$value}\n";
    }
    
    if (!empty($uploadedFiles)) {
        $textBody .= "\n--- 첨부파일 ---\n";
        foreach ($uploadedFiles as $fileName) {
            $textBody .= "- {$fileName}\n";
        }
    }

    // 고객 이메일 (회신용)
    $customerEmail = '';
    if (isset($data['d-email'])) $customerEmail = $data['d-email'];
    elseif (isset($data['da-email'])) $customerEmail = $data['da-email'];
    elseif (isset($data['g-email'])) $customerEmail = $data['g-email'];
    elseif (isset($data['gj-email'])) $customerEmail = $data['gj-email'];

    // Mailer를 사용하여 이메일 발송
    $result = array('ok' => false, 'error' => 'Unknown error');
    
    // SMTP 디버그 로그 파일
    $debugLogFile = $config['upload_dir'] . 'smtp_debug.log';
    
    try {
        $mailer = new Mailer($config['smtp_email'], $config['smtp_password']);
        
        // 디버그 모드 활성화 (SMTP 통신 로그 저장)
        ob_start(); // 출력 버퍼링 시작
        
        $result = $mailer->send(array(
            'fromEmail'   => $config['smtp_email'],
            'fromName'    => 'E2L 보험센터',
            'to'          => $config['to_email'],
            'replyTo'     => $customerEmail ? $customerEmail : $config['smtp_email'],
            'subject'     => $subject,
            'body'        => $htmlBody,
            'altBody'     => $textBody,
            'attachments' => $attachments,
            'debug'       => 2, // SMTP 디버그 레벨 2 (클라이언트 + 서버 메시지)
        ));
        
        // 디버그 로그 캡처
        $debugOutput = ob_get_clean();
        if (!empty($debugOutput)) {
            $debugLogData = "=== " . date('Y-m-d H:i:s') . " ===\n";
            $debugLogData .= "FormType: {$formType}\n";
            $debugLogData .= "To: {$config['to_email']}\n";
            $debugLogData .= "Result: " . ($result['ok'] ? 'OK' : 'FAILED') . "\n";
            if (!$result['ok']) {
                $debugLogData .= "Error: " . (isset($result['error']) ? $result['error'] : 'Unknown') . "\n";
            }
            $debugLogData .= "--- SMTP Debug Output ---\n";
            $debugLogData .= $debugOutput . "\n";
            $debugLogData .= "========================\n\n";
            @file_put_contents($debugLogFile, $debugLogData, FILE_APPEND | LOCK_EX);
        }
        
    } catch (Exception $e) {
        ob_end_clean(); // 버퍼 정리
        $result = array('ok' => false, 'error' => $e->getMessage());
        
        // 예외 로그 저장
        $exceptionLog = "=== " . date('Y-m-d H:i:s') . " ===\n";
        $exceptionLog .= "Exception: " . $e->getMessage() . "\n";
        $exceptionLog .= "File: " . $e->getFile() . " | Line: " . $e->getLine() . "\n";
        $exceptionLog .= "========================\n\n";
        @file_put_contents($debugLogFile, $exceptionLog, FILE_APPEND | LOCK_EX);
    }

    // 로그 저장 (항상 실행되도록)
    $logFile = $config['upload_dir'] . 'submissions.log';
    $logData = $now . " | " . $formType . " | " . $companyField . " | mail:" . ($result['ok'] ? 'Y' : 'N');
    if (!$result['ok'] && isset($result['error'])) {
        $logData .= " | error:" . $result['error'];
    }
    $logData .= "\n";
    
    // 로그 파일 쓰기 시도 (에러 무시하지 않고 확인)
    $logWritten = @file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
    if ($logWritten === false) {
        // 로그 파일 쓰기 실패 시 에러 로그에 기록
        error_log('[E2L] 로그 파일 쓰기 실패: ' . $logFile);
    }

    // 응답 (디버깅용으로 에러도 포함)
    if ($result['ok']) {
        echo json_encode(array(
            'ok' => true,
            'message' => '신청이 완료되었습니다. 담당자가 곧 연락드리겠습니다.'
        ));
    } else {
        // 이메일 실패 시 에러 정보도 함께 반환 (디버깅용)
        $errorMsg = isset($result['error']) ? $result['error'] : '알 수 없는 오류';
        echo json_encode(array(
            'ok' => true,
            'message' => '신청이 접수되었습니다. (이메일 발송 실패)',
            'error' => $errorMsg  // 디버깅용
        ));
    }

} catch (Exception $e) {
    // 에러 로그 저장
    $errorLogFile = __DIR__ . '/../uploads/error.log';
    $errorLogData = date('Y-m-d H:i:s') . " | Exception: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine() . "\n";
    @file_put_contents($errorLogFile, $errorLogData, FILE_APPEND | LOCK_EX);
    
    // 에러 응답
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array(
        'ok' => false,
        'error' => '서버 오류: ' . $e->getMessage()
    ));
}
