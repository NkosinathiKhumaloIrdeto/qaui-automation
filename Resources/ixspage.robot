*** Settings ***
Library     SeleniumLibrary
Resource  ./config.robot
Resource  ./common.robot


*** Keywords ***
Goto IXS Page
    sleep   40s
    wait until page contains    My Assigned Tasks
    click link  xpath=/html/body/div[1]/div[2]/div/div[1]/ul/li[7]/a
Search For Item
    [Arguments]  ${str_search_item}
    sleep   20s
    wait until page contains    IXS Status Request

    input text  xpath=//*[@id="request_genrefid"]    ${str_search_item}
    press key   xpath=//*[@id="request_genrefid"]    \ue007
    sleep   40s
Click Reprocess Button
    click button    //*[@id="button_submit_re-source"]
    wait until page contains    Re-transcode Request

Check Select Value
    @{items}=  get list items  xpath=//*[@id="process-platform"]
    ${list_length}=  get length  ${items}
    #element should contain  MobileDownload
    should be true  ${list_length} > 1

    #check drop down content - //*[@id="process-platform"] - shouldnt contain

req_status
    click link  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div[2]/div/table/tbody/tr/td[13]/a[1]
    sleep  5s
    element should contain  xpath=//*[@id="dialog-mamrequeue-task"]     Failure
    #click button  xpath=/html/body/div[9]/div[3]/div/button
check_legend_dialog
    click element  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[2]/div[2]/form/div[2]/img
    element should contain  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[3]/div/mam.robotdiv/div[1]/div[2]/div[2]/span[1]    Query file status in MAM


