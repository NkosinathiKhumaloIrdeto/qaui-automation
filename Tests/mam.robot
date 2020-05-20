*** Settings ***
Documentation  QA UI Automation
Resource  ../Resources/common.robot
Resource  ../Resources/mam.robot
Resource  ../Resources/config.robot

*** Test Cases ***
Launch Browser
    [Documentation]     Open browser, login
    [Tags]      Login Test
    common.launch_browser
    common.login  ${qa_ui_url}  ${qa_username}  ${qa_password}

Navigate To Mam
    [Documentation]     Once browser is opne, navigate to mam
    [Tags]      Mam page test
    mam.navigate_to_mam
    mam.search_for_item  ${search_item}
    mam.check_legend_dialog
    #mam.req_status
    common.exit_browser