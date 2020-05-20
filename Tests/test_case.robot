*** Settings ***
Documentation  QA UI Automation
Resource  ../Resources/config.robot
Resource  ingest.robot
Resource  programsearch.robot 

*** Test Cases ***
Setup
    #common.launch_browser
    ingest.Update Genref

Ingest Meta & Sources
    [Documentation]     Open browser, login
    ingest.Update XML - Genref & UID
    ingest.Update XML - start & end date
    ingest.Ingest XML to IXS
    log to console  Wait for meta to be ingested into MM
    sleep   60s

Drop sources
    ingest.Drop Source
    ingest.Ingest Image

Proceed To QA
    [Documentation]     Login, goto program search - QA-UI
    #login to qa ui
    common.login  ${qa_ui_url}  ${qa_username}  ${qa_password}
    sleep  ${sleep_timer_3}
    programsearch.Goto Program Search
    sleep  ${sleep_timer_3}

Search by genref
    [Documentation]     search for genref
    programsearch.Search For Items By Genref  ${Updated_genref}
    sleep  ${sleep_timer_3}

Validate Explora
    [Documentation]     explora
  #search for item
  #1. explora
    programsearch.Search For Platform  explora
    sleep  ${sleep_timer_1}
    programsearch.Assign item
    sleep  ${sleep_timer1}
    programsearch.Open program
    sleep  ${sleep_timer3}
    programsearch.Validate Metadata  IS20  Explora
    #check if countries are present

Validate Nano
    [Documentation]     nano
  #1. nano
    sleep  ${sleep_timer_1}
    press key   xpath=//*[@id="request_genrefid"]    \ue007
    sleep  ${sleep_timer_2}
    programsearch.Search For Platform  nano
    sleep  ${sleep_timer_1}
    programsearch.Assign item
    sleep  ${sleep_timer1}
    programsearch.Open program
    sleep  ${sleep_timer3}
    programsearch.Validate Metadata  IS20  Nano
    #check if countries are present

    press key   xpath=//*[@id="request_genrefid"]    \ue007
    sleep  ${sleep_timer_2}

Validate hevc
    [Documentation]     hevc
  #1. nano
    sleep  ${sleep_timer_1}
    press key   xpath=//*[@id="request_genrefid"]    \ue007
    sleep  ${sleep_timer_2}
    programsearch.Search For Platform  hevc
    sleep  ${sleep_timer_1}
    programsearch.Open program
    sleep  ${sleep_timer3}
    programsearch.Validate Metadata PullVod  IS20  Nano
    common.exit_browser
    #check if countries are present

*** Keywords ***

Validate Pull
    [Documentation]     pull
  #1. nano
    sleep  ${sleep_timer_1}
    press key   xpath=//*[@id="request_genrefid"]    \ue007
    sleep  ${sleep_timer_2}
    programsearch.Search For Platform  pull
    sleep  ${sleep_timer_1}
    programsearch.Open program
    sleep  ${sleep_timer3}
    programsearch.Validate Metadata PullVod  IS20  Explora
    #check if countries are present

    press key   xpath=//*[@id="request_genrefid"]    \ue007
    sleep  ${sleep_timer_2}