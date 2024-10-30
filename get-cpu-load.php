<?php
  # 현재 CPU 부하를 확인
  $idleCpu = exec('vmstat 1 2 | awk \'{ for (i=1; i<=NF; i++) if ($i=="id") { getline; getline; print $i }}\'');

  # CPU 부하 출력
  echo "<br /><p>";
  echo "Current CPU Load: ";
  echo "<b>".(100 - $idleCpu)."%</b>";
  echo "</p>";
