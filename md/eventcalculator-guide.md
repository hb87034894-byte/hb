# E2L 보험센터 HTML 코드 가이드

## 📋 목차
1. [HTML 구조 개요](#html-구조-개요)
2. [CSS 스타일 가이드](#css-스타일-가이드)
3. [JavaScript 기능](#javascript-기능)
4. [주요 컴포넌트](#주요-컴포넌트)

---

## HTML 구조 개요

### 기본 구조
```html
<!DOCTYPE html>
<html lang="ko">
<head>
  <!-- 메타 태그 및 외부 리소스 -->
</head>
<body>
  <!-- 페이지 콘텐츠 -->
</body>
</html>
```

### 주요 섹션
1. **상단 네비게이션** (`.top-nav`)
2. **모바일 메뉴** (`.mobile-menu-panel`)
3. **플로팅 연락처 버튼** (`.floating-contact`)
4. **메인 컨테이너** (`.container`)
   - 홈 화면 (`#section-home`)
   - 국내 행사보험 (`#section-domestic`)
   - 해외 행사보험 (`#section-global`)
   - 근재보험 (`#section-geunjae`)
5. **모달 창들** (개인정보, 이용약관 등)
6. **푸터** (`.footer-box`)

---

## CSS 스타일 가이드

### 1. 기본 스타일

#### 전체 리셋 및 기본 설정
```css
* { box-sizing: border-box; }
```
- 모든 요소에 `box-sizing: border-box` 적용하여 padding과 border를 width에 포함

#### Body 스타일
```css
body {
  font-family: 'Noto Sans KR', -apple-system, BlinkMacSystemFont, sans-serif;
  margin: 0;
  padding: 0;
  padding-bottom: 130px; /* 푸터 공간 확보 */
  background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
  min-height: 100vh;
  color: #333;
}
```
- **폰트**: Noto Sans KR 우선, 시스템 폰트 폴백
- **배경**: 회색 그라데이션
- **최소 높이**: 100vh로 전체 화면 채우기

### 2. 네비게이션 스타일

#### 상단 네비게이션
```css
.top-nav {
  position: sticky; /* 스크롤 시 상단 고정 */
  top: 0;
  z-index: 900;
  background: rgba(255,255,255,0.95); /* 반투명 배경 */
  backdrop-filter: blur(12px); /* 블러 효과 */
  border-bottom: 1px solid #e8ecf2;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
```

**핵심 개념:**
- `position: sticky`: 스크롤 시 상단에 고정
- `backdrop-filter: blur()`: 배경 블러 효과 (모던 브라우저)
- `rgba()`: 반투명 배경색

### 3. 카드 및 그리드 레이아웃

#### 서비스 카드 그리드
```css
.service-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* 3열 그리드 */
  gap: 20px;
  margin-bottom: 32px;
}
```

#### 입력 필드 그리드
```css
.input-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* 2열 그리드 */
  gap: 16px 24px; /* 행 간격 16px, 열 간격 24px */
}
```

**핵심 개념:**
- CSS Grid: 2차원 레이아웃 시스템
- `repeat(3, 1fr)`: 3개의 동일한 크기 열
- `gap`: 그리드 아이템 간 간격

### 4. 반응형 디자인

#### 미디어 쿼리
```css
@media (max-width: 700px) {
  .top-nav-menu { display: none; } /* 데스크톱 메뉴 숨김 */
  .mobile-menu-btn { display: flex; } /* 모바일 메뉴 버튼 표시 */
  .input-grid { grid-template-columns: 1fr; } /* 1열로 변경 */
  .service-cards { grid-template-columns: 1fr; } /* 1열로 변경 */
}
```

**핵심 개념:**
- 모바일 우선 접근법
- 700px 이하에서 레이아웃 변경
- 그리드를 1열로 변경하여 모바일 최적화

### 5. 애니메이션

#### 페이드인 애니메이션
```css
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.tab-section {
  display: none;
  animation: fadeIn 0.3s ease;
}
.tab-section.active {
  display: block;
}
```

#### 슬라이드업 애니메이션
```css
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
```

**핵심 개념:**
- `@keyframes`: 애니메이션 정의
- `transform`: GPU 가속을 활용한 부드러운 애니메이션
- `transition`: 상태 변화 시 애니메이션 적용

### 6. 테마 시스템

#### 테마별 색상
```css
/* 기본 테마 (파란색) */
.btn-primary {
  background: #10b981; /* 초록색 */
}

/* 보라색 테마 */
.purple-theme .btn-primary {
  background: #7c3aed;
}

/* 주황색 테마 */
.orange-theme .btn-primary {
  background: #ea580c;
}
```

**핵심 개념:**
- 클래스 기반 테마 시스템
- 부모 클래스로 전체 테마 변경
- CSS 특이성 활용

### 7. 보험료 비교 테이블 강조

#### C보험사 열 강조
```css
/* C보험사 헤더 강조 */
.compare-table th:last-child {
  background: rgba(255,255,255,0.2);
  font-weight: 700;
  position: relative;
}
.compare-table th:last-child::after {
  content: '⭐'; /* 별표 아이콘 추가 */
  margin-left: 4px;
}

/* C보험사 데이터 셀 강조 */
.compare-table td:last-child {
  background: #ecfdf5; /* 연한 초록색 배경 */
  font-weight: 700;
  color: #10b981; /* 초록색 텍스트 */
  font-size: 14px;
}
```

**핵심 개념:**
- `:last-child` 선택자: 마지막 자식 요소 선택
- `::after` 가상 요소: 콘텐츠 추가
- `content` 속성: 가상 요소에 텍스트/이미지 추가

---

## JavaScript 기능

### 1. 섹션 전환

```javascript
function showSection(section) {
  // 모든 메뉴 링크에서 active 클래스 제거
  document.querySelectorAll('.top-nav-menu a').forEach(a => 
    a.classList.remove('active')
  );
  
  // 선택된 섹션의 링크에 active 클래스 추가
  if (section !== 'home') {
    const activeLink = document.querySelector(
      `.top-nav-menu a[data-section="${section}"]`
    );
    if (activeLink) activeLink.classList.add('active');
  }
  
  // 모든 섹션 숨기기
  document.querySelectorAll('.main-section').forEach(s => 
    s.classList.remove('active')
  );
  
  // 선택된 섹션 표시
  document.getElementById(`section-${section}`).classList.add('active');
}
```

**핵심 개념:**
- `classList.add/remove`: 클래스 토글
- `querySelector`: CSS 선택자로 요소 찾기
- `data-*` 속성: 커스텀 데이터 저장

### 2. 폼 제출 (Fetch API)

```javascript
async function submitForm(type) {
  // FormData 객체 생성
  const formData = new FormData();
  formData.append('formType', 'domestic-quote');
  
  // 텍스트 필드 추가
  formFields.forEach(fieldId => {
    const el = document.getElementById(fieldId);
    if (el) formData.append(fieldId, el.value);
  });
  
  // 파일 필드 추가
  fileFields.forEach(fieldId => {
    const el = document.getElementById(fieldId);
    if (el && el.files && el.files[0]) {
      formData.append(fieldId, el.files[0]);
    }
  });
  
  // Fetch API로 서버에 전송
  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.ok) {
      // 성공 처리
      msg.textContent = '✅ 신청 완료!';
    } else {
      // 에러 처리
      msg.textContent = '❌ 오류: ' + result.error;
    }
  } catch (error) {
    // 네트워크 에러 처리
    console.error('Submit error:', error);
  }
}
```

**핵심 개념:**
- `async/await`: 비동기 처리
- `FormData`: 파일 업로드를 위한 데이터 형식
- `fetch API`: HTTP 요청
- `try/catch`: 에러 처리

### 3. 전체 동의 체크박스

```javascript
function toggleAll(prefix) {
  const allCheckbox = document.getElementById(prefix + '-all');
  const checkboxes = getConsentCheckboxes(prefix);
  
  // 전체 동의 체크박스 상태에 따라 모든 체크박스 동기화
  checkboxes.forEach(cb => cb.checked = allCheckbox.checked);
}

function checkAll(prefix) {
  const allCheckbox = document.getElementById(prefix + '-all');
  const checkboxes = getConsentCheckboxes(prefix);
  
  // 모든 체크박스가 체크되어 있으면 전체 동의도 체크
  allCheckbox.checked = checkboxes.every(cb => cb.checked);
}
```

**핵심 개념:**
- `forEach`: 배열 순회
- `every()`: 모든 요소가 조건을 만족하는지 확인
- 동적 ID 생성: `prefix + '-all'`

### 4. 아코디언 (FAQ)

```javascript
document.querySelectorAll('.accordion-header').forEach(btn => {
  btn.addEventListener('click', () => {
    const item = btn.parentElement;
    const section = item.closest('.faq-section');
    const wasActive = item.classList.contains('active');
    
    // 모든 아코디언 닫기
    section.querySelectorAll('.accordion-item').forEach(i => 
      i.classList.remove('active')
    );
    
    // 클릭한 항목이 닫혀있었으면 열기
    if (!wasActive) item.classList.add('active');
  });
});
```

**핵심 개념:**
- `addEventListener`: 이벤트 리스너 등록
- `closest()`: 가장 가까운 부모 요소 찾기
- 토글 로직: 열려있으면 닫고, 닫혀있으면 열기

---

## 주요 컴포넌트

### 1. 플로팅 연락처 버튼

**HTML 구조:**
```html
<div class="floating-contact">
  <div class="floating-contact-panel" id="floatingPanel">
    <!-- 연락처 정보 -->
  </div>
  <button class="floating-contact-btn" onclick="toggleFloatingContact()">
    📞
  </button>
</div>
```

**CSS:**
```css
.floating-contact {
  position: fixed; /* 화면에 고정 */
  right: 20px;
  bottom: 140px;
  z-index: 850;
}

.floating-contact-btn {
  width: 56px;
  height: 56px;
  border-radius: 50%; /* 원형 버튼 */
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  box-shadow: 0 4px 16px rgba(16,185,129,0.4);
  transition: all 0.3s ease;
}

.floating-contact-btn:hover {
  transform: scale(1.1); /* 호버 시 확대 */
}
```

### 2. 모달 창

**HTML 구조:**
```html
<div class="modal-overlay" id="privacyModal" onclick="closePrivacyModal(event)">
  <div class="modal-box" onclick="event.stopPropagation()">
    <!-- 모달 내용 -->
  </div>
</div>
```

**CSS:**
```css
.modal-overlay {
  display: none;
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.5); /* 반투명 배경 */
  z-index: 1000;
  align-items: center;
  justify-content: center;
}

.modal-overlay.active {
  display: flex;
}
```

**JavaScript:**
```javascript
function openPrivacyModal() {
  document.getElementById('privacyModal').classList.add('active');
  document.body.style.overflow = 'hidden'; /* 스크롤 방지 */
}

function closePrivacyModal(event) {
  if (!event || event.target === event.currentTarget) {
    document.getElementById('privacyModal').classList.remove('active');
    document.body.style.overflow = '';
  }
}
```

**핵심 개념:**
- `event.stopPropagation()`: 이벤트 버블링 방지
- 오버레이 클릭 시 모달 닫기
- `overflow: hidden`: 모달 열릴 때 배경 스크롤 방지

### 3. 탭 시스템

**HTML 구조:**
```html
<div class="inner-menu" id="domestic-tabs">
  <a class="active" data-tab="domestic-quote">견적신청</a>
  <a data-tab="domestic-apply">가입신청</a>
</div>
<div class="tab-wrapper">
  <div id="domestic-quote" class="tab-section active">...</div>
  <div id="domestic-apply" class="tab-section">...</div>
</div>
```

**JavaScript:**
```javascript
document.querySelectorAll('.inner-menu a[data-tab]').forEach(link => {
  link.addEventListener('click', () => {
    const tabId = link.dataset.tab;
    const menu = link.closest('.inner-menu');
    
    // 모든 탭 링크에서 active 제거
    menu.querySelectorAll('a').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
    
    // 모든 탭 섹션 숨기기
    const wrapper = menu.nextElementSibling;
    wrapper.querySelectorAll('.tab-section').forEach(t => 
      t.classList.remove('active')
    );
    
    // 선택된 탭 표시
    document.getElementById(tabId).classList.add('active');
  });
});
```

---

## 주요 CSS 기법 정리

### 1. Flexbox
```css
.container {
  display: flex;
  align-items: center; /* 수직 정렬 */
  justify-content: space-between; /* 수평 정렬 */
}
```

### 2. Grid
```css
.grid-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}
```

### 3. 가상 요소 (Pseudo-elements)
```css
.accordion-header::before {
  content: 'Q'; /* 콘텐츠 추가 */
  width: 24px;
  height: 24px;
  background: #10b981;
}
```

### 4. 가상 클래스 (Pseudo-classes)
```css
.btn:hover {
  background: #059669; /* 호버 시 */
}

.btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(16,185,129,0.1); /* 포커스 시 */
}

.field:last-child {
  margin-bottom: 0; /* 마지막 요소 */
}
```

### 5. CSS 변수 (사용 가능한 경우)
```css
:root {
  --primary-color: #10b981;
  --secondary-color: #64748b;
}

.btn-primary {
  background: var(--primary-color);
}
```

---

## 학습 포인트

### 1. 반응형 디자인
- 모바일 우선 접근법
- 미디어 쿼리 활용
- 유연한 그리드 시스템

### 2. 접근성
- 시맨틱 HTML 태그 사용
- 키보드 네비게이션 지원
- ARIA 속성 고려 (필요시)

### 3. 성능 최적화
- CSS 애니메이션 (GPU 가속)
- 이벤트 위임 활용
- 이미지 최적화

### 4. 코드 구조
- 재사용 가능한 컴포넌트
- 일관된 네이밍 컨벤션
- 주석 및 문서화

---

## 실전 팁

### 1. 디버깅
```javascript
// 콘솔 로그 활용
console.log('Form data:', formData);

// 브라우저 개발자 도구 활용
// - Elements 탭: HTML/CSS 확인
// - Console 탭: JavaScript 에러 확인
// - Network 탭: API 요청 확인
```

### 2. 브라우저 호환성
- `backdrop-filter`: 최신 브라우저만 지원
- `fetch API`: IE11 미지원 (polyfill 필요)
- CSS Grid: IE11 부분 지원

### 3. 성능 개선
- 이미지 lazy loading
- CSS/JS 파일 압축
- CDN 활용

---

## 참고 자료

- [MDN Web Docs](https://developer.mozilla.org/)
- [CSS-Tricks](https://css-tricks.com/)
- [Can I Use](https://caniuse.com/) - 브라우저 호환성 확인

---

**작성일**: 2025년
**버전**: 1.0

