<?php
// 메타데이터 서비스 v2를 위한 토큰 요청
$tokenUrl = 'http://169.254.169.254/latest/api/token';
$options = [
  'http' => [
    'method' => 'PUT',
    'header' => 'X-aws-ec2-metadata-token-ttl-seconds: 21600' // 토큰 유효 시간 설 정 (6시간)
  ]
];
$context = stream_context_create($options);
$token = file_get_contents($tokenUrl, false, $context);

// 메타데이터 요청 함수
function getMetadata($path, $token)
{
  $url = 'http://169.254.169.254/latest/meta-data/' . $path;
  $options = [
    'http' => [
      'header' => 'X-aws-ec2-metadata-token: ' . $token
    ]
  ];
  $context = stream_context_create($options);
  return file_get_contents($url, false, $context);
}

// 리전, 가용 영역, 인스턴스 ID 가져오기
$region = getMetadata('placement/region', $token);
$availabilityZone = getMetadata('placement/availability-zone', $token);
$instanceId = getMetadata('instance-id', $token);
$privateIp = getMetadata('local-ipv4', $token);
$publicIp = getMetadata('public-ipv4', $token);


echo "<table>";
echo "<tr><th width='40%'>Meta-Data</th><th width='60%'>Value</th></tr>";
echo "<tr><td>리전</td><td><i>" . $region . "</i></td><tr>";
echo "<tr><td>가용 영역</td><td><i>" . $availabilityZone . "</i></td><tr>";
echo "<tr><td>인스턴스 ID</td><td><i>" . $instanceId . "</i></td><tr>";
echo "<tr><td>프라이빗 IP</td><td><i>" . $privateIp . "</i></td><tr>";
echo "<tr><td>퍼블릭 IP</td><td><i>" . $publicIp . "</i></td><tr>";
echo "</table>";
