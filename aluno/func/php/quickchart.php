<?php
function grafico($corretas, $erradas){
$config = [
    'type' => 'bar',
	'format' => 'jpg',
    'data' => [
        'labels' => ['Dashboard'],
        'datasets' => [
            [
                'label' => 'Corretas',
                'data' => [$corretas],
                'backgroundColor' => '#55ff55',
                'borderColor' => '#000000',
                'borderWidth' => 3
            ],
			[
                'label' => 'Erradas',
                'data' => [$erradas],
                'backgroundColor' => '#ff5555',
                'borderColor' => '#000000',
                'borderWidth' => 3
            ],
        ]
    ]
];

$chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($config)) . '&width=200&height=200';


echo "<img src='$chartUrl' alt='Dashboard de feedback'>";
}
?>
