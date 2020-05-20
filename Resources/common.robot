*** Settings ***
Library     SeleniumLibrary
Resource  config.robot
Resource  ../Resources/programsearch.robot



*** Keywords ***
launch_browser
    log to console  ${qa_ui_url}_${browser}
    open browser  ${qa_ui_url}  ${browser}
    MAXIMIZE BROWSER WINDOW

launch_browser_url
    [Arguments]  ${str_url}
    sleep  5s
    open browser  ${str_url}  ${browser}
    MAXIMIZE BROWSER WINDOW


login
    [Arguments]  ${str_url}     ${str_username}     ${str_password}
    go to  ${str_url}
    wait until page contains  Metadata QA
    input text  xpath=//*[@id="m_username"]   ${str_username}
    input text  xpath=/html/body/div[1]/form/div[1]/input[2]  ${str_password}
    click button  xpath=//*[@id="button_login"]

Is Checkbox Checked
    [Arguments]    ${str_xpath}
    ${Is_Checkbox_Selected}=    Run Keyword And Return Status    Checkbox Should Be Selected    ${str_xpath}
    ${Actual_Chkbx_Value}=    Run Keyword If    '${Is_Checkbox_Selected}'== 'True'    Set Variable    Yes
    ...    ELSE IF    '${Is_Checkbox_Selected}'== 'False'    Set Variable    No
    [Return]    ${Actual_Chkbx_Value}


Get Select Items
    [Arguments]  ${str_obj_xpath}
    @{items}=  get list items  xpath=${str_obj_xpath}    STR
    [Return]  ${items}

Check Select Values
    [Arguments]     ${obj_items}    ${str_value}

    :FOR    ${element}    IN    @{obj_items}
    \    Log    ${element}
   # \    ${check_select_result}=     Run Keyword If    '${element}' == '${str_value}'    Set Variable    ${TRUE}
   # \    Run Keyword If    '${check_select_result}' == 'Yes'    Exit For Loop
    \    ${check_select_result}=     Run Keyword If    '${element}' == '${str_value}'    Set Variable    ${TRUE}
    \    Run Keyword If    ${check_select_result}    Exit For Loop
    \    Log    '${element}'

    [Return]    ${check_select_result}

softCheck Select Values Contains
    [Arguments]     ${obj_items}    ${str_value}

    :FOR    ${element}    IN    @{obj_items}
    \    Log    ${element}
    \    ${check_select_result}=     Run Keyword If    '${str_value}' in '${element}'    Set Variable    ${TRUE}
    \    Run Keyword If    ${check_select_result}    Exit For Loop
    \    Log    '${element}'

    [Return]    ${check_select_result}

Check Text Value
    [Arguments]  ${xpath_field}  ${strValue}
    ${strVal}  SeleniumLibrary.Get Element Attribute  xpath=${xpath_field}  value
    ${result}   evaluate  '${strVal}'=='${strValue}'
    [Return]  ${result}

Check Text Value_src
    [Arguments]  ${xpath_field}  ${strValue}
    ${strVal}  SeleniumLibrary.Get Element Attribute  xpath=${xpath_field}  src
    ${result}   evaluate  '${strVal}'=='${strValue}'
    [Return]  ${result}


Check Text Value2
    [Arguments]  ${xpath_field}  ${str_att}  ${strValue}
    ${strVal}  SeleniumLibrary.Get Element Attribute  xpath=${xpath_field}  ${str_att}
   # should be true  '${strValue}' in '${strVal}'
    Should Be True      "${strValue}" in "${strVal}"
    #${result}   evaluate  '${strVal}'=='${strValue}'
   # [Return]  ${result}

Open Dialog
    [Arguments]  ${xpath_button}
    click button  ${xpath_button}
    sleep  2s


Close Dialog
    Press Keys    None    ESC
    sleep  2s

exit_browser
    close browser


*** Variables ***
${check_select_result} =  ${FALSE}

