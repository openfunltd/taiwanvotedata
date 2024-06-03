<?php

// https://db.cec.gov.tw/_nuxt/36d558a.js
$map = json_decode('{"P0":"President","L0":"Legislator","C1":"Mayor","T1":"CouncilMember","C2":"CountyMayor","T2":"CountyCouncilMember","D1":"DistrictExecutive","R1":"DistrictRepresentatives","V0":"VillageChief","D2":"CityMayor","R2":"CityRepresentatives","N0":"NationalAssembly","P9":"ProvincialGovernor","T9":"ProvincialAssemblyCouncilMembers","REF":"Referendum","President":"P0","Legislator":"L0","Mayor":"C1","CouncilMember":"T1","CountyMayor":"C2","CountyCouncilMember":"T2","DistrictExecutive":"D1","DistrictRepresentatives":"R1","VillageChief":"V0","CityMayor":"D2","CityRepresentatives":"R2","NationalAssembly":"N0","ProvincialGovernor":"P9","ProvincialAssemblyCouncilMembers":"T9","Referendum":"REF"}');

$elc_subjects = json_decode(file_get_contents('https://db.cec.gov.tw/static/elections/configs/ELC_subjects.json'));
$output = fopen('php://output', 'w');
fputcsv($output, ['subject_id', 'subject_name', 'subject_enname', 'legislator_types']);

foreach ($elc_subjects as $elc_subject) {
    $subject_id = $elc_subject->subject_id;
    $subject_name = $elc_subject->subject_name;
    $subject_enname = $map->{$elc_subject->subject_id};
    $legislator_types = implode('&', array_map(function($type){
        return $type->type_id . '=' . $type->type_name;
    }, $elc_subject->legislator_types));
    fputcsv($output, [$subject_id, $subject_name, $subject_enname, $legislator_types]);

}
