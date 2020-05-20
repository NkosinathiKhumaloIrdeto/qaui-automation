*** Settings ***
Documentation  QA UI Automation
Resource  ../Resources/common.robot
Resource  ../Resources/config.robot

*** Keywords ***
Launch Browser
    [Documentation]     Open browser, login
    [Tags]      Login Test
    common.launch_browser
    common.login  ${qa_ui_url}  ${qa_username}  ${qa_password}

Navigate To Program Search
    [Documentation]     Once browser is opne, navigate to Program Search
    [Tags]      Mam page test

    programsearch.Goto Program Search

    #programsearch.check_legend_dialog
    #mam.req_status
    #common.exit_browser

Navigate Validate Content
    [Documentation]     Once browser is opne, navigate to Program Search
    programsearch.Search For Item   ${program_search}
    programsearch.Check Content   ${program_search}