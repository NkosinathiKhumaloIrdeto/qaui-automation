*** Settings ***
Library  XML
Library  robot.libraries.DateTime
Library  RequestsLibrary
Library  OperatingSystem
Library  robot.libraries.Process
Library  random
Resource  ../Resources/config.robot
Resource  ../Resources/common.robot
Resource  ./ixs_page_functions.robot

*** Variables ***

*** Test Cases ***
Start Process
    #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata

     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
     #10x
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    Ingest Metadata
    
    
*** Keywords ***
Ingest Metadata
    ${random_1}=  Evaluate    random.randint(99999, 999999)   random
    ${random_2}=  Evaluate    random.randint(99999, 999999)   random
    ${random_3}=  Evaluate    random.randint(99999, 999999)   random
    ${random_4}=  Evaluate    random.randint(99999, 999999)   random

    ${root_element}=  Parse XML  ${XmlFile}

    ${random_1}=  Convert To String  ${random_1}
    ${random_2}=  Convert To String  ${random_2}
    ${random_3}=  Convert To String  ${random_3}
    ${random_4}=  Convert To String  ${random_4}

    set global variable  ${root}  ${root_element}
    set global variable  ${Updated_genref}  ${random_1}
    set global variable  ${uid_one}  ${random_2}
    set global variable  ${uid_two}  ${random_3}
    set global variable  ${updated_uid}  ${random_4}AV

    #step 2
    Log To Console  New genref: ${Updated_genref}
    Should Be Equal  ${root.tag}  mediaservice
    Set Element Attribute  ${root}  genref  ${Updated_genref}  xpath=program
    Set Element Attribute  ${root}  uid  ${updated_uid}  xpath=program
    Log To Console  New genref: ${Updated_genref}
    #Save Xml  ${root}  ${XmlFile}

    ${current_time}=  Get Time  timestamp  NOW
    ${startdate}=  Subtract Time From Date  ${current_time}  1 day  result_format=%d-%m-%Y %H:%M:%S
    ${enddate}=  Add Time To Date  ${current_time}  7 days  result_format=%d-%m-%Y %H:%M:%S
    Set Elements Text  ${root}  ${startdate}  xpath=program/schedule/delivery/mediadevice/compatibility/countries/country/bouquet/startdate
    Set Elements Text  ${root}  ${enddate}  xpath=program/schedule/delivery/mediadevice/compatibility/countries/country/bouquet/enddate
    Save Xml  ${root}  ${XmlFile}

    #step 3
    Log to console  Sending Message to ixs
    ${xml}=  Element To String  ${root}
    ${body}=  Catenate  <api>  ${xml}  </api>
    Should Contain  ${body}  api
    Should Contain  ${body}  mediaservice
    Create Session  IXS  ${IXSurl}
    ${resp}=  Post Request  IXS  Metadata  data=${body}
    Should Be Equal As Strings  ${resp.status_code}  200