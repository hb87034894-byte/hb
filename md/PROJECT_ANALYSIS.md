# E2L 보험센터 프로젝트 분석 문서

> **프로젝트 개요**: 기업 맞춤 보험 서비스 제공을 위한 단일 페이지 웹 애플리케이션
> 
> **작성일**: 2025-01-XX
> **버전**: 1.0

---

## 📁 디렉토리 구조

```
C:\hb\
├── eventcalculator.html          # 메인 HTML 파일 (단일 페이지 애플리케이션)
├── api/
│   └── submit.php                # 백엔드 API 엔드포인트 (견적/가입 신청 처리)
├── lib/
│   └── mailer/                   # PHPMailer 라이브러리
│       ├── Mailer.php            # 메일 발송 래퍼 클래스
│       ├── PHPMailer.php         # PHPMailer 코어 라이브러리
│       ├── SMTP.php              # SMTP 처리
│       └── Exception.php         # 예외 처리
├── templates/                    # 템플릿 파일
│   ├── traveler_list_under5.csv
│   ├── traveler_list_under5.xlsx
│   ├── traveler_list_over5.csv
│   └── traveler_list_over5.xlsx.xlsx
├── uploads/                      # 업로드된 파일 저장 디렉토리
│   ├── submissions.log           # 신청 로그
│   ├── smtp_debug.log            # SMTP 디버그 로그
│   └── error.log                 # 에러 로그
└── md/                           # 마크다운 문서
    ├── index.md
    └── eventcalculator-guide.md
```

---

## 🎯 프로젝트 기능 개요

### 주요 서비스

1. **국내 행사보험** (Blue Theme)
   - 행사주최자배상책임보험
   - 견적 신청 및 가입 신청

2. **해외 행사보험** (Purple Theme)
   - 해외행사보험 (CGL)
   - 견적 신청

3. **해외여행자보험** (Indigo Theme)
   - 단체 해외여행자보험
   - 5인 미만/5인 이상 구분
   - 여행자 명단 관리

4. **근재보험** (Orange Theme)
   - 국내근로자재해보장보험
   - 견적 신청 및 가입 신청
   - 보험료 자동 계산 기능

---

## 📄 주요 파일 상세 분석

### 1. eventcalculator.html

**파일 크기**: 약 2,035줄

**구성 요소**:

#### 1.1 HTML 구조
- **메타데이터**: UTF-8 인코딩, 반응형 뷰포트
- **폰트**: Google Fonts (Noto Sans KR)
- **섹션 구조**:
  - `section-home`: 홈 화면
  - `section-domestic`: 국내 행사보험
  - `section-global`: 해외 행사보험
  - `section-travel`: 해외여행자보험
  - `section-geunjae`: 근재보험

#### 1.2 CSS 스타일링
- **디자인 시스템**: 4가지 테마 컬러
  - Blue: `#10b981` (국내 행사보험)
  - Purple: `#7c3aed` (해외 행사보험)
  - Indigo: `#4f46e5` (해외여행자보험)
  - Orange: `#ea580c` (근재보험)

- **레이아웃**:
  - 반응형 그리드 시스템
  - 모바일 우선 설계
  - Flexbox 및 CSS Grid 활용

- **주요 컴포넌트 스타일**:
  - `.top-nav`: 고정 상단 네비게이션
  - `.mobile-menu-panel`: 모바일 햄버거 메뉴
  - `.floating-contact`: 플로팅 연락처 버튼
  - `.service-card`: 서비스 카드
  - `.tab-section`: 탭 기반 콘텐츠
  - `.modal-overlay`: 모달 오버레이

#### 1.3 JavaScript 기능 (29개 함수)

##### 네비게이션 관련
- `toggleMobileMenu()`: 모바일 메뉴 토글
- `showSection(section)`: 섹션 전환 (첫 번째 탭 자동 활성화)

##### 폼 제출 관련
- `submitForm(type)`: 견적 신청 폼 제출 (domestic, global, travel, geunjae)
- `submitApply()`: 국내 행사보험 가입 신청
- `submitGeunjaeApply()`: 근재보험 가입 신청

##### 폼 리셋 관련
- `resetForm(type)`: 견적 신청 폼 초기화
- `resetApply()`: 국내 행사보험 가입 폼 초기화
- `resetGeunjaeApply()`: 근재보험 가입 폼 초기화

##### 근재보험 관련
- `calculateGeunjaePremium()`: 보험료 계산 (업종 요율 기반)
- `goToGeunjaeApply(limitType)`: 견적에서 가입으로 전환
- `goToGeunjaeApplyDirect()`: 가입 페이지 직접 이동
- `formatNumber(input)`: 숫자 포맷팅 (천 단위 구분)

##### 해외여행자보험 관련
- `setTravelType(type)`: 5인 미만/5인 이상 타입 설정
- `downloadTravelerTemplate()`: 여행자 명단 CSV 템플릿 다운로드
- `addTraveler()`: 여행자 항목 추가
- `removeTraveler(btn)`: 여행자 항목 제거
- `updateTravelerCount()`: 여행자 수 업데이트
- `updateTravelerListPlaceholder()`: 인원수에 따른 여행자 리스트 자동 생성
- `formatJumin(input)`: 주민등록번호 포맷팅

##### 모달 관련
- `openPrivacyModal()`: 개인정보 수집 동의 모달 열기 (보험 종류별 동적 콘텐츠)
- `closePrivacyModal(event)`: 개인정보 모달 닫기
- `openLegalModal(which)`: 법적 고지 모달 열기
- `closeLegalModal(event, which)`: 법적 고지 모달 닫기

##### 유틸리티 함수
- `parseKRW(v)`: KRW 문자열 파싱
- `fmtKRW(n)`: 숫자를 KRW 형식으로 포맷팅
- `goToApply(type)`: 견적 결과에서 가입 페이지로 이동
- `toggleAll(prefix)`: 전체 동의 체크박스 처리
- `checkAll(prefix)`: 개별 동의 체크박스 상태 확인
- `getConsentCheckboxes(prefix)`: 동의 체크박스 목록 반환

#### 1.4 주요 기능 설명

##### 해외여행자보험 동적 폼
- **5인 미만**: 개별 연락처 및 자택 주소 필수
- **5인 이상**: 기본 정보만 입력 (회사명, 사업자등록번호 필수)
- **여행자 명단**: 동적 추가/제거 기능
- **CSV 템플릿**: 클라이언트 사이드에서 생성하여 다운로드

##### 근재보험료 계산
```javascript
function calculateGeunjaePremium() {
  // 업종별 요율 사용
  // 연간 임금총액 × 업종 요율 × 보상한도 계수
  // NaN 체크 및 오류 처리 포함
}
```

##### 개인정보 모달 동적 콘텐츠
- 현재 활성화된 보험 섹션에 따라 개인정보 수집 항목 표시
- 각 보험 종류별로 다른 수집 항목 안내

---

### 2. api/submit.php

**기능**: 폼 제출 데이터를 이메일로 전송하는 API 엔드포인트

#### 2.1 주요 처리 흐름

1. **요청 검증**
   - POST 메서드만 허용
   - OPTIONS 요청 처리 (CORS)
   - JSON 응답 헤더 설정

2. **파일 업로드 처리**
   - `uploads/` 디렉토리에 저장
   - 파일명 안전 처리 (특수문자 제거)
   - 타임스탬프 접두사 추가

3. **이메일 본문 생성**
   - HTML 및 텍스트 형식 제공
   - 입력 필드 테이블 형태로 정리
   - 첨부파일 목록 포함

4. **메일 발송**
   - PHPMailer 래퍼 클래스 사용
   - SMTP: Office365 (smtp.office365.com:587)
   - 디버그 로그 저장

5. **로깅**
   - `submissions.log`: 신청 기록
   - `smtp_debug.log`: SMTP 통신 로그
   - `error.log`: 에러 로그

#### 2.2 설정 정보

```php
$config = [
    'smtp_email'    => 'simg_admin@simg.kr',
    'smtp_password' => 'simg716673!',
    'to_email'      => 'newbiz@simg.kr',
    'upload_dir'    => __DIR__ . '/../uploads/',
];
```

#### 2.3 지원하는 폼 타입

- `domestic-quote`: 국내 행사보험 견적 신청
- `domestic-apply`: 국내 행사보험 가입 신청
- `global-quote`: 해외 행사보험 견적 신청
- `geunjae-quote`: 근재보험 견적 신청
- `travel-quote`: 해외여행자보험 견적 신청 (추정)

---

### 3. lib/mailer/Mailer.php

**기능**: PHPMailer 래퍼 클래스

#### 3.1 주요 특징

- **SMTP 설정**: Office365 SMTP 서버
- **TLS 암호화**: STARTTLS 사용
- **UTF-8 인코딩**: 한글 지원
- **디버그 모드**: SMTP 통신 로그 캡처

#### 3.2 사용 예시

```php
$mailer = new Mailer($username, $password);
$result = $mailer->send([
    'fromEmail'   => 'simg_admin@simg.kr',
    'fromName'    => 'E2L 보험센터',
    'to'          => 'newbiz@simg.kr',
    'replyTo'     => $customerEmail,
    'subject'     => $subject,
    'body'        => $htmlBody,
    'altBody'     => $textBody,
    'attachments' => $attachments,
    'debug'       => 2,
]);
```

---

## 🎨 디자인 시스템

### 컬러 팔레트

| 보험 종류 | Primary Color | Background | Hover |
|----------|---------------|------------|-------|
| 국내 행사보험 | `#10b981` (Green) | `#ecfdf5` | `#059669` |
| 해외 행사보험 | `#7c3aed` (Purple) | `#f3e8ff` | `#6d28d9` |
| 해외여행자보험 | `#4f46e5` (Indigo) | `#eef2ff` | `#4338ca` |
| 근재보험 | `#ea580c` (Orange) | `#fff7ed` | `#c2410c` |

### 타이포그래피

- **폰트 패밀리**: 'Noto Sans KR', -apple-system, BlinkMacSystemFont, sans-serif
- **제목**: 24px-28px, font-weight: 700
- **본문**: 14px-15px, font-weight: 400
- **라벨**: 13px, font-weight: 600

### 간격 시스템

- **컨테이너 패딩**: 32px (데스크톱), 22px-26px (모바일)
- **그리드 간격**: 16px-24px
- **카드 패딩**: 22px-28px
- **버튼 패딩**: 12px 24px

---

## 📱 반응형 디자인

### 브레이크포인트

- **모바일**: `max-width: 700px`
- **데스크톱**: `max-width: 900px` (컨테이너)

### 모바일 최적화

- 햄버거 메뉴로 네비게이션 전환
- 그리드 레이아웃 1열 변환
- 플로팅 연락처 버튼 위치 조정
- 폰트 크기 조정

---

## 🔐 보안 및 개인정보 처리

### 개인정보 수집 동의

각 보험 종류별로 다른 개인정보 수집 항목:

1. **국내 행사보험**: 회사명, 이메일, 연락처, 행사정보
2. **해외 행사보험**: 회사명, 사업자번호, 담당자명, 행사정보
3. **해외여행자보험**: 신청자 정보, 여행자 명단(성명, 주민등록번호 등)
4. **근재보험**: 회사명, 사업자번호, 근로자 수, 임금총액

### 파일 업로드 보안

- 파일명 특수문자 제거
- 타임스탬프 기반 파일명 생성
- 파일 타입 검증 (accept 속성)

---

## 🧩 주요 기능 상세

### 1. 근재보험료 계산 로직

```javascript
// 업종별 요율 사용
const rate = parseFloat(industrySelect.value) || 0;
const wage = parseKRW(wageInput.value) || 0;
const limitMultiplier = parseFloat(limitSelect.value) || 1.0;

if (rate > 0 && wage > 0) {
  const premium = Math.round(wage * rate * limitMultiplier);
  // NaN 체크 및 포맷팅
}
```

### 2. 여행자 명단 관리

- **동적 필드 생성**: JavaScript로 DOM 조작
- **CSV 다운로드**: Blob API 사용, UTF-8 BOM 포함
- **인원수 연동**: 입력된 인원수에 따라 자동 항목 생성

### 3. 모달 시스템

- **오버레이 클릭 닫기**: 이벤트 위임 사용
- **동적 콘텐츠**: 보험 종류별 다른 내용 표시
- **body 스크롤 잠금**: 모달 열릴 때 적용

---

## 📊 데이터 흐름

```
사용자 입력
    ↓
JavaScript 폼 검증
    ↓
FormData 생성 (파일 포함)
    ↓
POST /api/submit.php
    ↓
파일 업로드 처리
    ↓
이메일 본문 생성 (HTML + Text)
    ↓
PHPMailer로 메일 발송
    ↓
로깅 (submissions.log, smtp_debug.log)
    ↓
JSON 응답 반환
```

---

## 🔧 기술 스택

### 프론트엔드
- **HTML5**: 시맨틱 마크업
- **CSS3**: Flexbox, Grid, 애니메이션
- **JavaScript (ES6+)**: 
  - async/await
  - Fetch API
  - DOM 조작
  - 이벤트 핸들링

### 백엔드
- **PHP 7.4+**: 서버 사이드 스크립팅
- **PHPMailer**: 이메일 발송 라이브러리
- **SMTP**: Office365

### 라이브러리
- **PHPMailer**: 이메일 발송
- **Google Fonts**: Noto Sans KR

---

## 🚀 배포 및 설정

### 필수 디렉토리 권한

```
uploads/        # 755 (쓰기 권한 필요)
api/            # 755
lib/            # 644
```

### 환경 변수 (하드코딩됨)

현재 `api/submit.php`에 하드코딩된 설정:

```php
'smtp_email'    => 'simg_admin@simg.kr',
'smtp_password' => 'simg716673!',
'to_email'      => 'newbiz@simg.kr',
```

**권장**: 환경 변수나 설정 파일로 분리

### API 엔드포인트

```javascript
const API_URL = './api/submit.php';
```

---

## 🐛 알려진 이슈 및 개선 사항

### 이슈
1. **보안**: SMTP 비밀번호 하드코딩
2. **에러 처리**: 일부 함수에서 예외 처리 부족
3. **파일 확장자**: `traveler_list_over5.xlsx.xlsx` 중복 확장자

### 개선 제안
1. **환경 변수 분리**: 민감 정보 별도 관리
2. **CSRF 토큰**: 폼 제출 시 CSRF 보호
3. **파일 크기 제한**: 업로드 파일 크기 검증 추가
4. **입력 검증 강화**: 서버 사이드 검증 추가
5. **로깅 개선**: 구조화된 로깅 시스템

---

## 📝 코드 품질 메트릭

- **총 코드 라인**: 약 2,500줄 (HTML + CSS + JS + PHP)
- **JavaScript 함수**: 29개
- **CSS 클래스**: 100개 이상
- **반응형 브레이크포인트**: 1개 (700px)

---

## 🔄 버전 관리

현재 버전에서는 다음과 같은 기능이 구현되어 있습니다:

- ✅ 4가지 보험 종류 지원
- ✅ 반응형 디자인
- ✅ 파일 업로드
- ✅ 이메일 발송
- ✅ 동적 폼 필드
- ✅ 모달 시스템
- ✅ 개인정보 처리 동의
- ✅ 근재보험료 계산
- ✅ 여행자 명단 관리

---

## 📞 연락처 정보

**이투엘 주식회사**
- 주소: 경기도 과천시 갈현동 545, 과천디테크타워 B동 1201호
- 대표전화: 1533-5013
- 이메일: newbiz@simg.kr
- 사업자등록번호: 789-81-01455
- 보험대리점등록번호: 제2019020043호
- 대표자: 유은진

---

## 📄 라이선스 및 법적 고지

- 준법감시필 제2025-05002호 (2025.12.22 ~ 2026.12.21)
- 광고심의기준에 따라 사전심의 완료
- 개인정보처리방침 준수

---

**문서 작성자**: AI Assistant  
**최종 업데이트**: 2025-01-XX  
**프로젝트 버전**: 1.0



