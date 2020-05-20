*** Settings ***
Library     SeleniumLibrary
Resource  ./config.robot
Resource  ./common.robot


*** Keywords ***
Goto Program Search
    sleep  ${sleep_timer_3}
    sleep  ${sleep_timer_3}
    wait until page contains    My Assigned Tasks List
    click link  xpath=/html/body/div[1]/div[2]/div/div[1]/ul/li[6]/a
Search For Items By Genref
    [Arguments]  ${str_search_item}
    wait until page contains    MediaManager Program(s) Search
    sleep   2s
    input text  xpath=//*[@id="request_genrefid"]    ${str_search_item}
    press key   xpath=//*[@id="request_genrefid"]    \ue007
    sleep   2s

Search For Platform
    [Arguments]  ${str_platform}
    sleep  ${sleep_timer_2}
    input text  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div/div[1]/div[2]/div/label/input    ${str_platform}
    press key   xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div/div[1]/div[2]/div/label/input    \ue007
Assign item
    click element  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div/div[2]/div/table/tbody/tr/td[15]/div/div/img
    sleep   3s
    wait until page contains    Are you sure you want to claim task?
    click element  xpath=/html/body/div[7]/div[3]/div/button[1]     #click yes in dialog

Open program
    sleep  ${sleep_timer_2}
    click element   xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div/div[2]/div/table/tbody/tr/td[3]
    sleep   ${sleep_timer_3}

Validate Metadata
    [Arguments]  ${str_region}  ${str_platform}
    ${result}=  common.Check Text Value     //*[@id="pg_rating"]    13
    should be true  ${result}
    ${result}=  common.Check Text Value     //*[@id="theme"]    Movies
    should be true  ${result}

    #Linear Channel
    ${result}=  common.Check Text Value     /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[2]/div[2]/div/div/div[17]/input    M-NetHD
    should be true  ${result}

    #Primary channel
    ${result}=  common.Check Text Value     /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[2]/div[2]/div/div/div[18]/input    HDT
    should be true  ${result}

    #Primary channel DETAILS
    ${result}=  common.Check Text Value     /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[2]/div[2]/div/div/div[19]/input    M-NetHD|101
    should be true  ${result}

    #SECODNARY channel
    ${result}=  common.Check Text Value     /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[2]/div[2]/div/div/div[20]/input    MW4
    should be true  ${result}

    #SECODNARY channel DETAILS
    ${result}=  common.Check Text Value     /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[2]/div[2]/div/div/div[21]/input    MNA|101
    should be true  ${result}

    log to console  http://10.29.103.239/files/${str_region}_${Updated_genref}_${str_platform}_m1.png
    #${result}=  common.Check Text Value_src     /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[1]/div[2]/div/div/div[17]/img    http://10.29.103.239/files/${str_region}_${Updated_genref}_${str_platform}_m1.png
    should be true  ${result}
    click element   xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[3]/div[1]/h4/a       #Pushvod
    sleep  2s
    click element  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[3]/div[2]/div/div/div[1]/div[1]/h4/a     #Schedule and Distribution
   # common.Check Select Values Contains  //*[@id="distribution_countries"]   Botswana

    @{items}=  get list items  //*[@id="distribution_countries"]
    ${list_length}=  get length  ${items}
    should be true  ${list_length} > 1

Validate Metadata PullVod
    [Arguments]  ${str_region}  ${str_platform}
    ${result}=  common.Check Text Value     //*[@id="pg_rating"]    13
    should be true  ${result}
    ${result}=  common.Check Text Value     //*[@id="theme"]    Movies
    should be true  ${result}

    #${result}=  common.Check Text Value_src     /html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[1]/div[2]/div/div/div[17]/img    http://10.29.103.239/files/${str_region}_${Updated_genref}_${str_platform}_m1.png
   # should be true  ${result}
    click element   xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[4]/div[1]/h4/a       #Pullvod
    sleep  2s
    click element  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[4]/div[2]/div/div/div[1]/div[1]/h4/a    #Schedule and Distribution
   # common.Check Select Values Contains  //*[@id="distribution_countries"]   Botswana

    @{items}=  get list items  //*[@id="distribution_countries"]
    ${list_length}=  get length  ${items}
    should be true  ${list_length} > 1

Search For Genref
    [Arguments]  ${str_search_item}
    sleep   ${sleep_timer_2}
    input text  xpath=//*[@id="request_genrefid"]    ${str_search_item}
    press key   xpath=//*[@id="request_genrefid"]    \ue007
    #wait until page contains     ${str_search_item}

  #goto schedules - PULLVOD\Schedule and Distribution

    click link  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[4]/div[1]/h4/a
    click link  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div[2]/form/div[1]/div[4]/div[2]/div/div/div[1]/div[1]/h4/a

    #Check list of countries
    @{items}=  get list items  //*[@id="distribution_countries"]
    ${list_length}=  get length  ${items}
    should be true  ${list_length} > 1

Is published
    [Arguments]  ${str_xpath}
    #/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div/div[2]/div/table/tbody/tr[1]
    common.Check Text Value2     ${str_xpath}  class   itempublished
    #should be true  ${result}

req_status
    click link  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div[2]/div/table/tbody/tr/td[13]/a[1]
    sleep  6s
    element should contain  xpath=//*[@id="dialog-mamrequeue-task"]     Failure
    #click button  xpath=/html/body/div[9]/div[3]/div/button
check_legend_dialog
    click element  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[2]/div[2]/form/div[2]/img
    element should contain  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[3]/div/mam.robotdiv/div[1]/div[2]/div[2]/span[1]    Query file status in MAM