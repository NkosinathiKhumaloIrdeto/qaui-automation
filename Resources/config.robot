*** Variables ***
${base_url_ixs}=    http://10.29.103.244
${base_url_mm}=    http://10.29.103.239
${qa_ui_url} =  ${base_url_mm}/metadata_qa/
${IXSurl}  ${base_url_ixs}:8652
${file_ops}=  http://10.29.103.239/metadata_qa/FileOperations.php
#${QAui}  http://10.29.103.239/metadata_qa
${MMurl}  http://10.29.103.239
${timesheetsServer}=  http://10.29.103.101/FileOperations.php
#http://10.10.153.127/metadata_qa/
${browser} =  headlessfirefox
${default_url} =  about:blank
${qa_username} =  robotapp
${qa_password} =  robot@123

#mam
${search_item} =  970199AV

#program sea
${program_search} =  1000090

#ixs status
${ixs_explora_video}=
${ixs_nano_video}=
${ixs_hevc_sd_video}=
${ixs_hevc_hd_video}=
${ixs_pull_sd_video}=
${ixs_pull_hd_video}=

${XmlFile}=  ${CURDIR}/ent.xml 
#${CURDIR}/../Resources/ent.xml
${videofrom}=  /mnt/Encoder_Area/Ardome/Robot/video.mxf
${videoto}=  /mnt/Encoder_Area/Ardome/AUTOMATION_SYSTEM/
${imagefrom}=  /mnt/MNet/Robot/explora.png
${imageto}=  /mnt/MNet/Images/
${updated_uid}=  23d2323
${root}=
${Updated_genref}=  323423

${sleep_timer_1}=  2s
${sleep_timer_2}=  6s
${sleep_timer_3}=  35s

${log_path_file}=  ../ingestlog.txt  #C:\\dev\\testing\\untitled\\logs\\log.txt