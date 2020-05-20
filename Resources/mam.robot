*** Settings ***
Library     SeleniumLibrary
Resource  ./config.robot


*** Keywords ***
navigate_to_mam
    sleep   4s
    wait until page contains    My Assigned Tasks
    click link  xpath=/html/body/div[1]/div[2]/div/div[1]/ul/li[8]/a
search_for_item
    [Arguments]  ${str_search_item}
    wait until page contains    MediaManager MAM Search
    sleep   5s
    input text  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div[1]/div[2]/div/label/input    ${str_search_item}
    press key   xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div[1]/div[2]/div/label/input    \ue007
    sleep   5s
req_status
    click link  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[3]/div/div/div/div/div[2]/div/table/tbody/tr/td[13]/a[1]
    sleep   6s
    element should contain  xpath=//*[@id="dialog-mamrequeue-task"]     Failure
    #click button  xpath=/html/body/div[9]/div[3]/div/button
check_legend_dialog
    click element  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[2]/div[2]/div[2]/form/div[2]/img
    element should contain  xpath=/html/body/div[1]/div[2]/div/div[2]/div[2]/div[3]/div/mam.robotdiv/div[1]/div[2]/div[2]/span[1]    Query file status in MAM