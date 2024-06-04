<?php

if (!file_exists(__DIR__ . '/../outputs/votetype.csv')) {
    throw new Exception('votetype.csv not found');
}

$output = fopen(__DIR__ . '/../outputs/vote.csv', 'w');
$output_cols = [
    'type_id',
    'subject_id',
    'legislator_type_id',
    'session',
    'theme_name',
    'vote_date',
    'legislator_desc',
];
fputcsv($output, $output_cols);

foreach (['ELC', 'BEL', 'RCL'] as $type) {
    $url = sprintf("https://db.cec.gov.tw/static/elections/configs/{$type}_subjects.json");
    $subjects = json_decode(file_get_contents($url));
    foreach ($subjects as $subject) {
        $subject_id = $subject->subject_id;
        $url = "https://db.cec.gov.tw/static/elections/list/{$type}_{$subject_id}.json";
        $ele_datas = json_decode(file_get_contents($url));
        foreach ($ele_datas as $ele_data) {
            if ($ele_data->time_items ?? false) {
                foreach ($ele_data->time_items as $time_item) {
                    foreach ($time_item->theme_items as $theme_item) {
                        fputcsv($output, array_map(function($col) use ($theme_item) {
                            return $theme_item->{$col};
                        }, $output_cols));
                    }
                }
                continue;
            }
            foreach ($ele_data->theme_items as $theme_item) {
                fputcsv($output, array_map(function($col) use ($theme_item) {
                    return $theme_item->{$col};
                }, $output_cols));
            }
        }
    }
}


