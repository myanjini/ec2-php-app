<?php
  # 현재 사용자가 생성된 부하 여부를 추적할 수 있게 세션을 시작
  session_start();

  # 페이지를 5초마다 자동 새로고침 => CPU 부하 상태를 지속적으로 업데이트
  echo "<meta http-equiv=\"refresh\" content=\"5,URL=/load.php\" />";

  # 현재 CPU 부하를 확인
  # vmstat 1 2 : CPU 통계를 1초 간격으로 2회 수집
  # awk '...'  : id 필드(CPU 사용률 중 idle을 의미)의 두번째 값을 반환
  $idleCpu = exec('vmstat 1 2 | awk \'{ for (i=1; i<=NF; i++) if ($i=="id") { getline; getline; print $i }}\'');

  # CPU가 50% 이상 유휴 상태이면 부하를 생성
  if ($idleCpu > 50) {
    # /dev/zero 데이터를 100MB 블록 크기로 500번 읽어 gzip으로 압축한 후 다시 압축 해제해서 /dev/null로 출력
    echo exec('dd if=/dev/zero bs=100M count=500 | gzip | gzip -d  > /dev/null &');
    echo "Generating CPU Load! (auto refresh in 5 seconds)";
  } else {
    echo "Under High CPU Load! (auto refresh in 5 seconds)";
  }
