*** Settings ***
Documentation  QA UI Automation
Resource  ../Resources/common.robot
Resource  ../Resources/config.robot
Resource  ../Resources/ixspage.robot

*** Keywords ***
Launch Browser
    [Documentation]     Open browser, login
    [Tags]      Login Test
    common.launch_browser
    common.login  ${qa_ui_url}  ${qa_username}  ${qa_password}

Navigate To IXS Page

    ixspage.Goto IXS Page
    sleep  40s
    ixspage.Search For Item   ${Updated_genref}

    ${isMobileDownload}   common.Is Checkbox Checked  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/div[2]/div/div/div[10]/label[9]/input
    #check if mp4 files have been processed
    run keyword if  '${isMobileDownload}'=='Yes'   page should not contain  .mp4

    common.Open Dialog   xpath=//*[@id="button_submit_re-source"]
#### Change remove DOWNLOAD_MOBILE option from QA UI
    #Call function to get list

    @{items}=  common.Get Select Items  //*[@id="process-platform"]
    #Check for single item in list
    ${bool_mobile_download}=  common.Check Select Values     ${items}    DOWNLOAD_MOBILE
    #should not be true  ${bool_mobile_download}
    should not be true  ${bool_mobile_download}
    common.Close Dialog

   # common.Open Dialog   xpath=//*[@id="button_submit_re-protect"]
   # @{protected_items}=   common.Get Select Items  //*[@id="process-protect-file-name"]
     #  ${bool_protect_mp4}=   common.Check Select Values Contains   ${protected_items}    .mp4
   # should not be true  ${bool_protect_mp4}
   # common.Close Dialog

#### Check that button is removed
    element should not be visible  xpath=//*[@id="button_submit_re-cdn"]
    #ixspage.Check Select Value  ${}
    #[Arguments]  ${obj_items} ${str_value}

    #programsearch.Check Content   ${program_search}
    #programsearch.check_legend_dialog
    #mam.req_status
    #common.exit_browser
