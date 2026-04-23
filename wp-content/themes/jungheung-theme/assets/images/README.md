# 테마 이미지

테마 코드가 참조하는 이미지 파일들입니다. 두 파일은 git 으로 추적되니 바꾸면 커밋해야 사이트에 반영됩니다.

| 파일명        | 용도                                                                 | 권장 포맷                |
|---------------|----------------------------------------------------------------------|-------------------------|
| `logo.png`    | 헤더의 주 브랜드 로고 (가로형, 예: "광주복싱.com")                      | PNG, 투명 배경, 500×150~ |
| `character.png` | 히어로 마스코트, 404 페이지 아이콘 (캐릭터 이미지)                   | PNG/JPG, 정사각 500×500~ |

## 폴백 순서 (헤더)

1. WP 관리자에서 **외모 → 사용자 정의 → 사이트 아이덴티티**에 "로고"를 업로드했다면 → 그 로고 사용
2. 위 `logo.png` 파일이 있으면 → 브랜드 로고 모드
3. `character.png` 만 있으면 → 캐릭터 로고 + 사이트 제목 텍스트 조합
4. 둘 다 없으면 → 사이트 제목 텍스트만

## 이미지 교체 방법

```bash
# Downloads 의 파일을 배치
cp ~/Downloads/logo.png      wp-content/themes/jungheung-theme/assets/images/logo.png
cp ~/Downloads/character.png wp-content/themes/jungheung-theme/assets/images/character.png

git add wp-content/themes/jungheung-theme/assets/images/
git commit -m "로고/캐릭터 이미지 교체"
git push
```

맥미니에서 `./scripts/deploy.sh` 로 바로 반영 (테마 파일은 볼륨 마운트라 재시작 없이 즉시 적용).
