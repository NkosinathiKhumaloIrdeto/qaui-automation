<?php

session_start();
$role = $_SESSION['role_id'];

require('scripts/Httpful/Bootstrap.php');
\Httpful\Bootstrap::init();

$user_role_id = $_SESSION['role_id'];

$genRefId = trim($_POST['genRefId']);

include_once 'includes/Config.php';

$service_url = IXS_URL . '/StatusRequest';

$response = \Httpful\Request::post($service_url)
        ->body('<StatusRequest><GenRef>' . $genRefId . '</GenRef></StatusRequest>')
        ->sendsXml()
        ->send();

$entries = json_decode($response->body, true);

$metadataPlatforms = array();

// Metadata entries
$metadata_entries = $entries['MetadataEntries'];

//$entries

$regions = "";

if ($metadata_entries) {

    //get regions
    $sRegion = "";

    foreach($entries['MetadataEntries'] as $key=>$value){

        $sRegion = $value['Region'];

         $regions .= "<option value='$sRegion'>$sRegion</option>";
        
    }

    // found metadata entries
    $metadataPlatforms = array();
    if ($entries['MetadataEntries']['Metadata (IS20)']) {
        $region_name = $entries['MetadataEntries']['Metadata (IS20)']['Region'];
        
        $is20_data = $entries['MetadataEntries']['Metadata (IS20)'];
        
        if ($entries['MetadataEntries']['Metadata (IS20)']['LastSynergyScheduleEntry']) {
            $is20_platform = '';
            foreach($entries['MetadataEntries']['Metadata (IS20)']['LastSynergyScheduleEntry'] as $key=>$value){
                if($key != "SeriesTitle") {
                    if (array_values($value)[0]) {
						if($key == 'ExploraPushVOD'){
							$metadataPlatforms['PUSH_EXP_PVR'] = 'Explora PushVOD';
							$metadataPlatforms['PUSH_EXP2_PVR'] = 'Nano PushVOD';
						}
						if($key == 'HDPushVOD'){
							$metadataPlatforms['PUSH_HDP_PVR'] = 'HD PushVOD';
						}
						if($key == 'SDPushVOD'){
							$metadataPlatforms['PUSH_SDP_PVR'] = 'SD PushVOD';
						}
						if($key == 'ExploraPullVOD'){
							$metadataPlatforms['DOWNLOAD_EXP_PVR'] = 'Explora PullVOD';
                            $metadataPlatforms['DOWNLOAD_EXP2_PVR'] = 'Nano/HEVC PullVOD';
						}
						if($key == 'WebStreaming' || $key == 'MobileStreaming'){
							$metadataPlatforms['STREAMING_PC'] = 'Web/Mobile Streaming';
						}
						if($key == 'MobileDownload'){
							$metadataPlatforms['DOWNLOAD_MOBILE'] = 'Mobile Download';
						}
                        $platformStartDateLabel = key($value) . ' Start Date';
                        $platformStartDateValue = array_values($value['BOUQUET'])[0]['Start Date'];

                        $platformExpiryDateLabel = key($value) . ' Expiry Date';
                        $platformExpiryValue = array_values($value['BOUQUET'])[0]['End Date'];

                        $bouquetLabel = key($value) . ' Bouquet';

                        //if(key($value['BOUQUET']) != ''){
                        $bouquetValue = key($value['BOUQUET']);
                        if (count($value['BOUQUET']) > 1) {
                            foreach ($value['BOUQUET'] as $key => $bq) {
                                $is20_date .= "<div class='form-group' style='width: 30%;'><label>$bouquetLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$key}'/></div>";
                                $is20_date .= "<div class='form-group' style='width: 30%;'><label>$platformStartDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bq['Start Date']}'/></div>";
                                $is20_date .= "<div class='form-group' style='width: 30%;'><label>$platformExpiryDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bq['End Date']}'/></div>";
                            }
                        } else {
                            $is20_date .= "<div class='form-group' style='width: 30%;'><label>$bouquetLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bouquetValue}'/></div>";
                            $is20_date .= "<div class='form-group' style='width: 30%;'><label>$platformStartDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$platformStartDateValue}'/></div>";
                            $is20_date .= "<div class='form-group' style='width: 30%;'><label>$platformExpiryDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$platformExpiryValue}'/></div>";
                        }
                        //}
                        $is20_platform .= "<label id='status_platform'><input type='checkbox' checked disabled > $key</label>";
                    } else {
                        if ($value == 1)
                            $is20_platform .= "<label id='status_platform'><input type='checkbox' checked disabled > $key</label>";
                        else
                            $is20_platform .= "<label id='status_platform'><input type='checkbox' disabled > $key</label>";
                    }
                }
            }
        }
        $is20_platform_entries = "";
        $is20_metadata_entries = "<div class='form-group'>
                                    <label>GenRef</label>
                                    <input readonly type='text' class='form-control' name='service' id='service' value='{$is20_data['GenRef']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>Uid</label>
                                    <input readonly type='text' class='form-control' name='vod_type' value='{$is20_data['Uid']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>SeasonId</label>
                                    <input readonly type='text' class='form-control' name='platform' value='{$is20_data['SeasonId']}'/>
                                </div>
				                <div class='form-group'>
                                    <label>Series Title</label>
                                    <input readonly type='text' class='form-control' name='platform' value='{$is20_data['SeasonTitle']}'/>
                                </div>

                                <div class='form-group'>
                                    <label>MetadataEntryId</label>
                                    <input readonly type='text' class='form-control' name='genref_id' id='genref_id' value='{$is20_data['MetadataEntryId']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>LastSynergyCRUD</label>
                                    <input readonly type='text' class='form-control' name='title' id='title' value='{$is20_data['LastSynergyCRUD']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>LastMetadataReceived</label>
                                    <input readonly type='text' class='form-control' name='title' id='title' value='{$is20_data['LastMetadataReceived']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>NVPVideoMetaId</label>
                                    <input readonly type='text' class='form-control' name='program_title' id='program_title' value='{$is20_data['NVPVideoMetaId']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>NVPProgramId</label>
                                    <input readonly type='text' class='form-control' name='series_title' value='{$is20_data['NVPProgramId']}'/>
                                </div>
								<div class='col-sm-12'>
                                    $is20_platform
                                </div>
								<div class='form-group' style='width:95% !important; margin-top:15px !important;'>
									<div class='panel panel-default'>
										<div class='panel-heading'>
											<h4 class='panel-title'>Dates & Bouquet</h4>
										</div>

										<div id='collapseOne' class='panel-collapse collapse in'>
											<div class='panel-body'>
												<div id='qa_form_container'>
													$is20_date
												</div>
											</div>
										</div>
									</div>
								</div>
                                
                                ";
    }else {
        $is20_metadata_entries = 'No IS20 metadata entry was made for GenRef ID ' . $genRefId;
    }

    if ($entries['MetadataEntries']['Metadata (E36B)']) {
        $e36b_data = $entries['MetadataEntries']['Metadata (E36B)'];

        if ($entries['MetadataEntries']['Metadata (E36B)']['LastSynergyScheduleEntry']) {
            $e36b_platform = '';
            foreach($entries['MetadataEntries']['Metadata (E36B)']['LastSynergyScheduleEntry'] as $key=>$value){
                if($key != "SeriesTitle") {
                    if (array_values($value)[0]) {
						if($key == 'ExploraPushVOD'){
							$metadataPlatforms['PUSH_EXP_PVR'] = 'Explora PushVOD';
							$metadataPlatforms['PUSH_EXP2_PVR'] = 'Nano PushVOD';
						}
						if($key == 'HDPushVOD'){
							$metadataPlatforms['PUSH_HDP_PVR'] = 'HD PushVOD';
						}
						if($key == 'SDPushVOD'){
							$metadataPlatforms['PUSH_SDP_PVR'] = 'SD PushVOD';
						}
						if($key == 'ExploraPullVOD'){
							$metadataPlatforms['DOWNLOAD_EXP_PVR'] = 'Explora PullVOD';
                            $metadataPlatforms['DOWNLOAD_EXP2_PVR'] = 'Nano/HEVC PullVOD';
						}
						if($key == 'WebStreaming' || $key == 'MobileStreaming'){
							$metadataPlatforms['STREAMING_PC'] = 'Web/Mobile Streaming';
						}
						if($key == 'MobileDownload'){
							$metadataPlatforms['DOWNLOAD_MOBILE'] = 'Mobile Download';
						}
                        $platformStartDateLabel = key($value) . ' Start Date';
                        $platformStartDateValue = array_values($value['BOUQUET'])[0]['Start Date'];

                        $platformExpiryDateLabel = key($value) . ' Expiry Date';
                        $platformExpiryValue = array_values($value['BOUQUET'])[0]['End Date'];

                        $bouquetLabel = key($value) . ' Bouquet';

                        //if(key($value['BOUQUET']) != ''){
                        $bouquetValue = key($value['BOUQUET']);
                        if (count($value['BOUQUET']) > 1) {
                            foreach ($value['BOUQUET'] as $key => $bq) {
                                $e36b_date .= "<div class='form-group' style='width: 30%;'><label>$bouquetLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$key}'/></div>";
                                $e36b_date .= "<div class='form-group' style='width: 30%;'><label>$platformStartDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bq['Start Date']}'/></div>";
                                $e36b_date .= "<div class='form-group' style='width: 30%;'><label>$platformExpiryDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bq['End Date']}'/></div>";
                            }
                        } else {
                            $e36b_date .= "<div class='form-group' style='width: 30%;'><label>$bouquetLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bouquetValue}'/></div>";
                            $e36b_date .= "<div class='form-group' style='width: 30%;'><label>$platformStartDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$platformStartDateValue}'/></div>";
                            $e36b_date .= "<div class='form-group' style='width: 30%;'><label>$platformExpiryDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$platformExpiryValue}'/></div>";
                        }
                        //}
                        $e36b_platform .= "<label id='status_platform'><input type='checkbox' checked disabled > $key</label>";
                    } else {
                        if ($value == 1)
                            $e36b_platform .= "<label id='status_platform'><input type='checkbox' checked disabled > $key</label>";
                        else
                            $e36b_platform .= "<label id='status_platform'><input type='checkbox' disabled > $key</label>";
                    }
                }
            }
        }

        $e36b_metadata_entries = "<div class='form-group'>
                                    <label>GenRef</label>
                                    <input readonly type='text' class='form-control' name='service' id='service' value='{$e36b_data['GenRef']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>Uid</label>
                                    <input readonly type='text' class='form-control' name='vod_type' value='{$e36b_data['Uid']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>SeasonId</label>
                                    <input readonly type='text' class='form-control' name='platform' value='{$e36b_data['SeasonId']}'/>
                                </div>
				<div class='form-group'>
                                    <label>Series Title</label>
                                    <input readonly type='text' class='form-control' name='platform' value='{$e36b_data['SeasonTitle']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>MetadataEntryId</label>
                                    <input readonly type='text' class='form-control' name='genref_id' id='genref_id' value='{$e36b_data['MetadataEntryId']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>LastSynergyCRUD</label>
                                    <input readonly type='text' class='form-control' name='title' id='title' value='{$e36b_data['LastSynergyCRUD']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>LastMetadataReceived</label>
                                    <input readonly type='text' class='form-control' name='title' id='title' value='{$e36b_data['LastMetadataReceived']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>NVPVideoMetaId</label>
                                    <input readonly type='text' class='form-control' name='program_title' id='program_title' value='{$e36b_data['NVPVideoMetaId']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>NVPProgramId</label>
                                    <input readonly type='text' class='form-control' name='series_title' value='{$e36b_data['NVPProgramId']}'/>
                                </div>
                                <div class='col-sm-12'>
                                    $e36b_platform
                                </div>
								<div class='form-group' style='width:95% !important; margin-top:15px !important;'>
									<div class='panel panel-default'>
										<div class='panel-heading'>
											<h4 class='panel-title'>Dates & Bouquet</h4>
										</div>

										<div id='collapseOne' class='panel-collapse collapse in'>
											<div class='panel-body'>
												<div id='qa_form_container'>
													$e36b_date
												</div>
											</div>
										</div>
									</div>
								</div>";
    }else {
        $e36b_metadata_entries = 'No E36B metadata entry was made for GenRef ID ' . $genRefId;
    }

    if ($entries['MetadataEntries']['Metadata (E36A)']) {
        $e36a_data = $entries['MetadataEntries']['Metadata (E36A)'];

        if ($entries['MetadataEntries']['Metadata (E36A)']['LastSynergyScheduleEntry']) {
            $e36a_platform = '';
            foreach($entries['MetadataEntries']['Metadata (E36A)']['LastSynergyScheduleEntry'] as $key=>$value){
                if($key != "SeriesTitle"){
                    if (array_values($value)[0]) {
						if($key == 'ExploraPushVOD'){
							$metadataPlatforms['PUSH_EXP_PVR'] = 'Explora PushVOD';
							$metadataPlatforms['PUSH_EXP2_PVR'] = 'Nano PushVOD';
						}
						if($key == 'HDPushVOD'){
							$metadataPlatforms['PUSH_HDP_PVR'] = 'HD PushVOD';
						}
						if($key == 'SDPushVOD'){
							$metadataPlatforms['PUSH_SDP_PVR'] = 'SD PushVOD';
						}
						if($key == 'ExploraPullVOD'){
							$metadataPlatforms['DOWNLOAD_EXP_PVR'] = 'Explora PullVOD';
                            $metadataPlatforms['DOWNLOAD_EXP2_PVR'] = 'Nano/HEVC PullVOD';
						}
						if($key == 'WebStreaming' || $key == 'MobileStreaming'){
							$metadataPlatforms['STREAMING_PC'] = 'Web/Mobile Streaming';
						}
						if($key == 'MobileDownload'){
							$metadataPlatforms['DOWNLOAD_MOBILE'] = 'Mobile Download';
						}
                        $platformStartDateLabel = key($value) . ' Start Date';
                        $platformStartDateValue = array_values($value['BOUQUET'])[0]['Start Date'];

                        $platformExpiryDateLabel = key($value) . ' Expiry Date';
                        $platformExpiryValue = array_values($value['BOUQUET'])[0]['End Date'];

                        $bouquetLabel = key($value) . ' Bouquet';

                        //if(key($value['BOUQUET']) != ''){
                        $bouquetValue = key($value['BOUQUET']);
                        if (count($value['BOUQUET']) > 1) {
                            foreach ($value['BOUQUET'] as $key => $bq) {
                                $e36a_date .= "<div class='form-group' style='width: 30%;'><label>$bouquetLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$key}'/></div>";
                                $e36a_date .= "<div class='form-group' style='width: 30%;'><label>$platformStartDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bq['Start Date']}'/></div>";
                                $e36a_date .= "<div class='form-group' style='width: 30%;'><label>$platformExpiryDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bq['End Date']}'/></div>";
                            }
                        } else {
                            $e36a_date .= "<div class='form-group' style='width: 30%;'><label>$bouquetLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$bouquetValue}'/></div>";
                            $e36a_date .= "<div class='form-group' style='width: 30%;'><label>$platformStartDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$platformStartDateValue}'/></div>";
                            $e36a_date .= "<div class='form-group' style='width: 30%;'><label>$platformExpiryDateLabel</label><input readonly type='text' class='form-control' name='series_title' value='{$platformExpiryValue}'/></div>";
                        }
                        //}

                        $e36a_platform .= "<label id='status_platform'><input type='checkbox' checked disabled > $key</label>";
                    } else {
                        if ($value == 1)
                            $e36a_platform .= "<label id='status_platform'><input type='checkbox' checked disabled > $key</label>";
                        else
                            $e36a_platform .= "<label id='status_platform'><input type='checkbox' disabled > $key</label>";
                    }
                }
            }
        }

        $e36a_metadata_entries = "<div class='form-group'>
                                    <label>GenRef</label>
                                    <input readonly type='text' class='form-control' name='service' id='service' value='{$e36a_data['GenRef']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>Uid</label>
                                    <input readonly type='text' class='form-control' name='vod_type' value='{$e36a_data['Uid']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>SeasonId</label>
                                    <input readonly type='text' class='form-control' name='platform' value='{$e36a_data['SeasonId']}'/>
                                </div>
				<div class='form-group'>
                                    <label>Series Title</label>
                                    <input readonly type='text' class='form-control' name='platform' value='{$e36a_data['SeasonTitle']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>MetadataEntryId</label>
                                    <input readonly type='text' class='form-control' name='genref_id' id='genref_id' value='{$e36a_data['MetadataEntryId']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>LastSynergyCRUD</label>
                                    <input readonly type='text' class='form-control' name='title' id='title' value='{$e36a_data['LastSynergyCRUD']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>LastMetadataReceived</label>
                                    <input readonly type='text' class='form-control' name='title' id='title' value='{$e36a_data['LastMetadataReceived']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>NVPVideoMetaId</label>
                                    <input readonly type='text' class='form-control' name='program_title' id='program_title' value='{$e36a_data['NVPVideoMetaId']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>NVPProgramId</label>
                                    <input readonly type='text' class='form-control' name='series_title' value='{$e36a_data['NVPProgramId']}'/>
                                </div>
                                <div class='col-sm-12'>
                                    $e36a_platform
                                </div>
								<div class='form-group' style='width:95% !important; margin-top:15px !important;'>
									<div class='panel panel-default'>
										<div class='panel-heading'>
											<h4 class='panel-title'>Dates & Bouquet</h4>
										</div>

										<div id='collapseOne' class='panel-collapse collapse in'>
											<div class='panel-body'>
												<div id='qa_form_container'>
													$e36a_date
												</div>
											</div>
										</div>
									</div>
								</div>";
    }else {
        $e36a_metadata_entries = 'No E36A metadata entry was made for GenRef ID ' . $genRefId;
    }
} else {
    // no metadata entries
    $is20_metadata_entries = 'No IS20 metadata entry was made for GenRef ID ' . $genRefId;
    $e36b_metadata_entries = 'No E36B metadata entry was made for GenRef ID ' . $genRefId;
    $e36a_metadata_entries = 'No E36A metadata entry was made for GenRef ID ' . $genRefId;
}

$metadataPlatforms = array_unique($metadataPlatforms);

$mPlatformOptions = '';
if (!empty($metadataPlatforms)) {

    foreach ($metadataPlatforms as $key => $mPlatform) {
       if($key !== "DOWNLOAD_MOBILE"){
            $mPlatformOptions .= "<option value='$key'>$mPlatform</option>";
       }
    }
}

echo "<style>
        .vid_table > tbody > tr > td {font-size:10pt}
        .vid_table > thead > tr > th {
            border-bottom: 0px;
            background: #fff !important;
            color: #333;
            text-shadow: 0;
            font-size:10pt;
        }
  </style>";

// Video entries
$video_entries = $entries['Videos'];
$v_entries = 
"<table class='table  vid_table' >
    <thead>
    <th width='45%'>Video File Name</th>
    <th width='15%'>Video Type</th>
    <th width='15%'>Video Processed DateTime</th>
    <th width='10%'>File Origin</th>
</thead>
<tbody>";

if ($video_entries) {
    // found video entries
    $vSourceOptions = "";
    $vSourceRegion = "";
    $vTranscodeOptions = "";
    $vProtectOptions = "";
    $counter = 0;

    foreach ($video_entries as $video) {

        // latest source videos
        if ($video['VideoType'] == 'Source' && $video['latest'] == 'true') {
            $vSourceOptions .= "<option value='{$video['Origin']}'>{$video['VideoFileName']}</option>";
            
        }
        // latest transcoded videos
        if ($video['VideoType'] == 'Transcoded' && $video['latest'] == 'true') {
        
                if(strpos(strtoupper($video['VideoFileName']),strtoupper('.MP4')) == 0){
                    $vTranscodeOptions .= "<option value='{$video['VideoFileName']}'>{$video['VideoFileName']}</option>";
                }
                
        
            
        }
        // latest protected videos
        if ($video['VideoType'] == 'Protected' && $video['latest'] == 'true') {
            $vProtectOptions .= "<option value='{$video['Origin']}'>{$video['VideoFileName']}</option>";
        }
        // get source types
        $vTypes[] = $video['VideoType'];

        $metadataVideoTypes[] = $video['VideoType'];
        
        $qaUser = $video['Message'] !== "" ? "<span style='display:block; padding:5px;background:#ddd; font-style:italic; font-size=8pt; color:#212121'><b>Manually triggered by</b> {$video['Message']}</span>":"";  

        $v_entries .= "<tr>
        <td>{$video['VideoFileName']} <br><br>$qaUser</td>
        <td>{$video['VideoType']}</td>
        <td>{$video['VideoProcessedDateTime']}</td>
        <td>{$video['Origin']}</td>
    </tr>";

                                $counter++;                         
    }
    $v_entries .= "</tbody></table>";
    $vTypes = array_unique($vTypes);
} else {
    // no video entries
    $v_entries = 'No video entries was made for GenRef ID ' . $genRefId;
}

$metadataVideoTypes = array_unique($metadataVideoTypes);

$vTypesString = implode(",", $metadataVideoTypes);

// Image entries
$image_entries = $entries['Images'];

if ($image_entries) {
    // found image entries
    foreach ($image_entries as $image) {
        $imageProcessedDateTime = date("Y-m-d H:i:s", strtotime($image['ImageProcessedDateTime']));
        $i_entries .= "<div class='form-group' style='width: 50%;'>
                                    <label>ImageFileName</label>
                                    <input readonly type='text' class='form-control' name='metadata_title' id='metadata_title' value='{$image['ImageFileName']}'/>
                                </div>
                                <div class='form-group' style='width: 15%;'>
                                    <label>ImageProcessedDateTime</label>
                                    <input readonly type='text' class='form-control' name='metadata_season' id='metadata_season' value='{$imageProcessedDateTime}'/>
                                </div>
				<div class='form-group' style='width: 10%;'>
                                    <label>File Origin:</label>
                                    <input readonly type='text' class='form-control' name='metadata_season' id='metadata_season' value='{$image['Origin']}'/>
                                </div>";
    }
} else {
    // no image entries
    $i_entries = 'No image entries was made for GenRef ID ' . $genRefId;
}

// Ad entries
$adverts_entries = $entries['Ads'];
if ($adverts_entries) {
    // found ad entries
    foreach ($adverts_entries as $ad) {

        $ad_entries .= "<div class='form-group'>
                                    <label>Region</label>
                                    <input readonly type='text' class='form-control' name='metadata_title' id='metadata_title' value='{$ad['Region']}'/>
                                </div>
                                <div class='form-group'>
                                    <label>Ad Creation Date</label>
                                    <input readonly type='text' class='form-control' name='metadata_series' id='metadata_series' value='{$ad['AdCreationDate']}'/>
                                </div>";
        foreach ($ad['File'] as $key => $value) {
            $ad_entries .= "<div class='form-group'>
                                    <label>$key</label>
                                    <input readonly type='text' class='form-control' name='metadata_season' id='metadata_season' value='{$value}'/>
                                </div>";
        }
    }
} else {
    // no ad entries
    $ad_entries = 'No ad entries was made for GenRef ID ' . $genRefId;
}
// Subtitle entries
$subtitle_entries = $entries['Subtitles'];

if ($subtitle_entries) {
    // found subtitle entries
    foreach ($subtitle_entries as $sub) {

        $subtitle_logs .= "<div class='form-group' style='width: 30%;'>
                                    <label>Filename</label>
                                    <input readonly type='text' class='form-control' name='metadata_title' id='metadata_title' value='{$sub['Filename']}'/>
                                </div>
                                <div class='form-group' style='width: 30%;'>
                                    <label>Uid</label>
                                    <input readonly type='text' class='form-control' name='metadata_series' id='metadata_series' value='{$sub['Uid']}'/>
                                </div>
				<div class='form-group' style='width: 30%;'>
                                    <label>ProcessedDateTime</label>
                                    <input readonly type='text' class='form-control' name='metadata_series' id='metadata_series' value='{$sub['ProcessedDateTime']}'/>
                                </div>";
    }
} else {
    // no subtitle entries
    $subtitle_logs = 'No subtitle entries was made for GenRef ID ' . $genRefId;
}


if ($role == 1 || $role == 13) {
    $re_processbtns = "<button type='button' class='btn btn-primary' id='button_submit_re-source' data-toggle='modal' data-target='#reProcessTranscodeContent' data-whatever='transcode'>Re-Transcode</button>
                <button type='button' class='btn btn-primary' id='button_submit_re-protect' data-toggle='modal' data-target='#reProcessProtectContent' data-whatever='protect'>Re-Protect</button>";
                //MCA-3977 - No longer processing DPR <button type='button' class='btn btn-primary' id='button_submit_re-cdn' data-toggle='modal' data-target='#reProcessCDNContent' data-whatever='cdn'>Re-Upload CDN</button>";
}


$display_response = "<div class='panel-group' id='accordion'>
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h4 class='panel-title'>IS20 - Metadata Status Log</h4>
        </div>
        <div id='collapseOne' class='panel-collapse collapse in'>
            <div class='panel-body'>
                <div id='qa_form_container'>
                    $is20_metadata_entries
                </div>
            </div>
        </div>
    </div>
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h4 class='panel-title'>
                E36B - Metadata Status Log
            </h4>
        </div>
        <div id='collapseOne' class='panel-collapse collapse in'>
            <div class='panel-body'>
                <div id='qa_form_container'>
                    $e36b_metadata_entries
                </div>
            </div>
        </div>
    </div>
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h4 class='panel-title'>
                E36A - Metadata Status Log
            </h4>
        </div>
        <div id='collapseOne' class='panel-collapse collapse in'>
            <div class='panel-body'>
                <div id='qa_form_container'>
                    $e36a_metadata_entries
                </div>
            </div>
        </div>
    </div>
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h4 class='panel-title'>
                Video Content Status Log
            </h4>
        </div>
        <div id='collapseOne' class='panel-collapse collapse in'>
            <div class='panel-body'>
                <div id='login_form_container'>
                    $v_entries
                </div>
            </div>
        </div>
    </div>
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h4 class='panel-title'>
                Image Content Status Log
            </h4>
        </div>
        <div id='collapseOne' class='panel-collapse collapse in'>
            <div class='panel-body'>
                <div id='login_form_container'>
                    $i_entries
                </div>
            </div>
        </div>
    </div>
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h4 class='panel-title'>
                Ads Status Log
            </h4>
        </div>
        <div id='collapseOne' class='panel-collapse collapse in'>
            <div class='panel-body'>
                <div id='login_form_container'>
                    $ad_entries
                </div>
            </div>
        </div>
    </div>
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h4 class='panel-title'>
                Subtitle Status Log
            </h4>
        </div>
        <div id='collapseOne' class='panel-collapse collapse in'>
            <div class='panel-body'>
                <div id='login_form_container'>
                    $subtitle_logs
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' value='$vTypesString' name='vTypes' id='vTypes'>
    <div>
        <div class='col-sm-9 col-sm-offset-3'>
            <div id='processing_buttons'>
                $re_processbtns
            </div>
        </div>
    </div>

    <!-- Transcode Modal -->
    <div class='modal fade' id='reProcessTranscodeContent' tabindex='-1' role='dialog' aria-labelledby='reProcessTranscodeContentLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='reProcessTranscodeContentLabel'>Re-transcode Request</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form>
                        <div class='form-group'>
                            <label for='recipient-name' class='col-form-label'>Select Platform:</label>
                            <select name='process-platform' id='process-platform'>
                                <option value=''>All</option>
                                $mPlatformOptions
                            </select>
                            <label for='recipient-name' class='col-form-label'>Select File to Process:</label>
                            <select name='process-file-name' id='process-file-name'>
                                $vSourceOptions
                            </select>
                        </div>
						
						 <div class='form-group'>
                            <label for='message-text' class='col-form-label'>Select Region:</label>
                            <select name='process-region' id='process-region'>
                                <option value='' selected='selected'>All</option>
                                $regions
                            </select>
                        </div>
						
                        <div class='form-group'>
                            <label for='message-text' class='col-form-label'>Reason for Re-processing:</label>
                            <select name='process-transacode-message' id='process-transacode-message'>
                                <option value='No STL file'>No STL file</option>
                                <option value='Partial transcodes'>Partial transcodes</option>
                                <option value='Platforms added to schedule'>Platforms added to schedule</option>
                                <option value='Platforms removed from schedule'>Platforms removed from schedule</option>
                                <option value='Partial Source'>Partial Source</option>
                                <option value='Exception'>Exception</option>
                                <option value='Protect failed'>Protect failed</option>
                                <option value='Transcode failed'>Transcode failed</option>
                            </select>
                        </div>
                        <div class='form-group exception-reason-div'>
                            <label for='message-text' class='col-form-label'>Message:</label>
                            <textarea rows='4' id='process-freetext'></textarea>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal' id='button_close'>Close</button>
                    <button type='button' class='btn btn-primary' id='button_submit_re'>Submit Request</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Protect Modal -->
    <div class='modal fade' id='reProcessProtectContent' tabindex='-1' role='dialog' aria-labelledby='reProcessProtectContentLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='reProcessProtectContentLabel'>Re-protect Request</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form>
                        <div class='form-group'>
                            <label for='recipient-name' class='col-form-label'>Select File to Process:</label>
                            <select name='process-protect-file-name' id='process-protect-file-name'>
                                $vTranscodeOptions
                            </select>
                        </div>
                        <div class='form-group'>
                            <label for='message-text' class='col-form-label'>Reason for Re-processing:</label>
                            <select name='process-protect-message' id='process-protect-message'>
                                <option value='Protect failed'>Protect failed</option>
                                <option value='Network Failure'>Network Failure</option>
                                <option value='RRM not available'>RRM not available</option>
                                <option value='No offer available'>No offer available</option>
                                <option value='Exception'>Exception</option>
                            </select>
                        </div>
                        <div class='form-group exception-reason-protect-div'>
                            <label for='message-text' class='col-form-label'>Message:</label>
                            <textarea rows='4' id='protect-freetext'></textarea>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal' id='button_close'>Close</button>
                    <button type='button' class='btn btn-primary' id='button_submit_re_protect'>Submit Request</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CDN Modal -->
    <div class='modal fade' id='reProcessCDNContent' tabindex='-1' role='dialog' aria-labelledby='reProcessCDNContentLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='reProcessCDNContentLabel'>Re-upload to CDN Request</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form>
                        <div class='form-group'>
                            <label for='recipient-name' class='col-form-label'>Select File to Process:</label>
                            <select name='process-cdn-file-name' id='process-cdn-file-name'>
                                $vProtectOptions
                            </select>
                        </div>
                        <div class='form-group'>
                            <label for='message-text' class='col-form-label'>Reason for Re-processing:</label>
                            <select name='process-cdn-message' id='process-cdn-message'>
                                <option value='CDN full'>CDN full</option>
                                <option value='FTP failure'>FTP failure</option>
                                <option value='Network Failure'>Network Failure</option>
                                <option value='Not replicated to CDN'>Not replicated to CDN</option>
                                <option value='Partial Upload'>Partial Upload</option>
                                <option value='Update Schedule'>Update Schedule</option>
                                <option value='Exception'>Exception</option>
                            </select>
                        </div>
                        <div class='form-group exception-reason-cdn-div'>
                            <label for='message-text' class='col-form-label'>Message:</label>
                            <textarea rows='4' id='cdn-freetext'></textarea>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal' id='button_close'>Close</button>
                    <button type='button' class='btn btn-primary' id='button_submit_re_cdn'>Submit Request</button>
                </div>
            </div>
        </div>
    </div>
    <style>

        .exception-reason-div, .exception-reason-protect-div, .exception-reason-cdn-div {display:none;}

    </style>
    <script type='text/javascript'>

        $(document).ready(function () {

            var videoTypeString = $('#vTypes').val().toUpperCase();

            if (videoTypeString.indexOf('SOURCE') == -1) {
                $('#button_submit_re-source').hide();
            }
            if (videoTypeString.indexOf('TRANSCODED') == -1) {
                $('#button_submit_re-protect').hide();
            }
            if (videoTypeString.indexOf('PROTECTED') == -1) {
                $('#button_submit_re-cdn').hide();
            }

//TRANSCODE
            $('#process-transacode-message').change(function () {

                var selectedValue = $(this).find(':selected').text();

                if (selectedValue.toUpperCase() == 'EXCEPTION') {

                    $('.exception-reason-div').css('display', 'block');

                } else {

                    $('.exception-reason-div').css('display', 'none');

                    $('#process-freetext').val('');

                }

            });

//PROTECT
            $('#process-protect-message').change(function () {

                var selectedValue = $(this).find(':selected').text();

                if (selectedValue.toUpperCase() == 'EXCEPTION') {

                    $('.exception-reason-protect-div').css('display', 'block');

                } else {

                    $('.exception-reason-protect-div').css('display', 'none');

                    $('#protect-freetext').val('');

                }

            });

//CDN
            $('#process-cdn-message').change(function () {

                var selectedValue = $(this).find(':selected').text();

                if (selectedValue.toUpperCase() == 'EXCEPTION') {

                    $('.exception-reason-cdn-div').css('display', 'block');

                } else {

                    $('.exception-reason-cdn-div').css('display', 'none');

                    $('#cdn-freetext').val('');

                }

            });


            $('#button_submit_re').click(fnProcessSource);

            $('#button_submit_re_protect').click(fnProcessProtect);

            $('#button_submit_re_cdn').click(fnProcessCDN);

            var filename = $('#process-file-name').find(':selected').text();
            var fileOrigin = $('#process-file-name').find(':selected').val();
            var fileRegion = $('#process-region').find(':selected').val();
            var filenameProtect = $('#process-protect-file-name').find(':selected').text();
            var fileProtectOrigin = $('#process-protect-file-name').find(':selected').val();
            var fileCDNOrigin = $('#process-cdn-file-name').find(':selected').val();
            var filenameCDN = $('#process-cdn-file-name').find(':selected').text();
            var platform = $('#process-platform').find(':selected').val();
            var message = $('#process-transacode-message').find(':selected').text();
            var messageProtect = $('#process-protect-message').find(':selected').text();
            var messageCDN = $('#process-cdn-message').find(':selected').text();

            $('#process-platform').change(function () {
                platform = $('#process-platform option:selected').val();
            });
            $('#process-transacode-message').change(function () {
                message = $('#process-transacode-message option:selected').text();
            });
            $('#process-protect-message').change(function () {
                messageProtect = $('#process-protect-message option:selected').text();
            });

            $('#process-file-name').change(function () {

                filename = $('#process-file-name option:selected').text();
				fileOrigin = $('#process-file-name').find(':selected').val();															 

            });

            $('#process-region').change(function () {
                fileRegion = $('#process-region option:selected').val();
            });


            $('#process-protect-file-name').change(function () {
                filenameProtect = $('#process-protect-file-name option:selected').text();
				fileProtectOrigin = $('#process-protect-file-name').find(':selected').val();
            });
            $('#process-cdn-file-name').change(function () {
                filenameCDN = $('#process-cdn-file-name option:selected').text();
				fileCDNOrigin = $('#process-cdn-file-name').find(':selected').val();									
            });

            function fnProcessProtect() {

                var str_message = $('#process-protect-message').find(':selected').text();

                var exceptionReason = '';

                if (str_message.toUpperCase() == 'EXCEPTION') {

                    exceptionReason = $('#protect-freetext').val();
                    
                     if (exceptionReason.length == 0) {
                        
                            alert('Please provide an Exception Message');
                            
                            return;

                       }

                    if (exceptionReason.length > 255) {

                        alert('255 character limit reached. Please ensure you have less than 255 characters for your Exception Message');

                        return;

                    }

                }

                $('#button_submit_re_protect').text('Submitting Requesting...');
                $('#button_submit_re_protect').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    data: 'filename=' + filenameProtect + '&message=' + messageProtect + '&origin=' + fileProtectOrigin + '&ExceptionReason=' + exceptionReason,
                    url: 'reprocess_protect_request.php',
                    success: function (data) {
                        alert('Request submitted successfully');
                        $('#button_submit_re_protect').text('Submit Request');
                        $('#button_submit_re_protect').prop('disabled', false);
                    },
                    error: function (data) {
                        $('#button_submit_re_protect').text('Submit Request');
                        $('#button_submit_re_protect').prop('disabled', false);
                        alert('An error occurred please try again');
                    }
                });

            }

            function fnProcessCDN() {

                var str_message = $('#process-cdn-message').find(':selected').text();

                var exceptionReason = '';

                if (str_message.toUpperCase() == 'EXCEPTION') {

                    exceptionReason = $('#cdn-freetext').val();

                     if (exceptionReason.length == 0) {
                        
                            alert('Please provide an Exception Message');
                            
                            return;

                       }

                    if (exceptionReason.length > 255) {

                        alert('255 character limit reached. Please ensure you have less than 255 characters for your Exception Message');

                        return;

                    }

                }

                $('#button_submit_re_cdn').text('Submitting Requesting...');
                $('#button_submit_re_cdn').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    data: 'filename=' + filenameCDN + '&message=' + messageCDN + '&origin=' + fileCDNOrigin + '&exceptionReason=' + exceptionReason,
                    url: 'reprocess_cdn_request.php',
                    success: function (data) {
                        
                        alert('Request submitted successfully');
                        $('#button_submit_re_cdn').text('Submit Request');
                        $('#button_submit_re_cdn').prop('disabled', false);
                    },
                    error: function (data) {
                        $('#button_submit_re_cdn').text('Submit Request');
                        $('#button_submit_re_cdn').prop('disabled', false);
                        alert('An error occurred please try again');
                    }
                });

            }

            function fnProcessSource() {

                var str_message = $('#process-transacode-message').find(':selected').text();

                var exceptionReason = '';

                if (str_message.toUpperCase() == 'EXCEPTION') {

                    exceptionReason = $('#process-freetext').val();

                       if (exceptionReason.length == 0) {
                        
                            alert('Please provide an Exception Message');
                            
                            return;

                       }

                    if (exceptionReason.length > 255) {

                        alert('255 character limit reached. Please ensure you have less than 255 characters for your Exception Message');

                        return;

                    }

                }
                $('#button_submit_re').text('Submitting Requesting...');
                $('#button_submit_re').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    data: 'filename=' + filename + '&message=' + message + '&platform=' + platform + '&origin=' + fileOrigin + '&ExceptionReason=' + exceptionReason + '&fileRegion=' + fileRegion,
                    url: 'reprocess_request.php',
                    success: function (data) {
                        alert('Request submitted successfully');
                        $('#button_submit_re').text('Submit Request');
                        $('#button_submit_re').prop('disabled', false);
                    },
                    error: function (data) {
                        $('#button_submit_re').text('Submit Request');
                        $('#button_submit_re').prop('disabled', false);
                        alert('An error occurred please try again');
                    }
                });
            }
        });
    </script>
</div>"
;

echo $display_response;

