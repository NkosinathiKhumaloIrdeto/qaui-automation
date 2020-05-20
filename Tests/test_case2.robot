*** Settings ***
Documentation  QA UI Automation
Resource  ../Resources/config.robot
Resource  ingest.robot
Resource  programsearch.robot
Library  RequestsLibrary
Library  OperatingSystem

*** Test Cases ***
Setup
	

    #common.launch_browser
    ${last_genref}=     Get File    ${log_path_file}
    set global variable  ${Updated_genref}  ${last_genref}
	log to console	Genref:${last_genref}
	log to console	Test started, wating 12 minutes for pullvod/hevc to publish
	sleep	720s

Proceed To QA
    [Documentation]     Login, goto program search - QA-UI
    #login to qa ui
    common.launch_browser_url   ${qa_ui_url}
    sleep  ${sleep_timer_2}
    common.login  ${qa_ui_url}  ${qa_username}  ${qa_password}
    sleep  ${sleep_timer_2}
    programsearch.Goto Program Search
    sleep  ${sleep_timer_2}

Search by genref
    [Documentation]     search for genref
    programsearch.Search For Items By Genref  ${Updated_genref}
    sleep  ${sleep_timer_2}

Validate Explora
    [Documentation]     PULLVOD
    #search for item
    #1. pull
    programsearch.Search For Platform  pullvod
    sleep  ${sleep_timer1}
    programsearch.Is published  /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div/div[2]/div/table/tbody/tr[1]

Validate
    [Documentation]     hevcpullvod
    #search for item
    #1. pull
    programsearch.Search For Platform  hevcpullvod
    sleep  ${sleep_timer1}
    programsearch.Is published  /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div/div[2]/div/table/tbody/tr
    create file  ${log_path_file}
    common.exit_browser
