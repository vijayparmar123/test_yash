<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/talent/v4beta1/common.proto

namespace GPBMetadata\Google\Cloud\Talent\V4Beta1;

class Common
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        \GPBMetadata\Google\Protobuf\Timestamp::initOnce();
        \GPBMetadata\Google\Protobuf\Wrappers::initOnce();
        \GPBMetadata\Google\Type\Date::initOnce();
        \GPBMetadata\Google\Type\Latlng::initOnce();
        \GPBMetadata\Google\Type\Money::initOnce();
        \GPBMetadata\Google\Type\PostalAddress::initOnce();
        \GPBMetadata\Google\Type\Timeofday::initOnce();
        \GPBMetadata\Google\Api\Annotations::initOnce();
        $pool->internalAddGeneratedFile(
            '
�2
(google/cloud/talent/v4beta1/common.protogoogle.cloud.talent.v4beta1google/protobuf/timestamp.protogoogle/protobuf/wrappers.protogoogle/type/date.protogoogle/type/latlng.protogoogle/type/money.proto google/type/postal_address.protogoogle/type/timeofday.protogoogle/api/annotations.proto"n
TimestampRange.

start_time (2.google.protobuf.Timestamp,
end_time (2.google.protobuf.Timestamp"�
LocationI
location_type (22.google.cloud.talent.v4beta1.Location.LocationType2
postal_address (2.google.type.PostalAddress$
lat_lng (2.google.type.LatLng
radius_miles ("�
LocationType
LOCATION_TYPE_UNSPECIFIED 
COUNTRY
ADMINISTRATIVE_AREA
SUB_ADMINISTRATIVE_AREA
LOCALITY
POSTAL_CODE
SUB_LOCALITY
SUB_LOCALITY_1
SUB_LOCALITY_2
NEIGHBORHOOD	
STREET_ADDRESS
"�
RequestMetadata
domain (	

session_id (	
user_id (	
allow_missing_ids (<
device_info (2\'.google.cloud.talent.v4beta1.DeviceInfo"&
ResponseMetadata

request_id (	"�

DeviceInfoG
device_type (22.google.cloud.talent.v4beta1.DeviceInfo.DeviceType

id (	"l

DeviceType
DEVICE_TYPE_UNSPECIFIED 
WEB

MOBILE_WEB
ANDROID
IOS
BOT	
OTHER"Q
CustomAttribute
string_values (	
long_values (

filterable ("W
SpellingCorrection
	corrected (
corrected_text (	
corrected_html (	"�	
CompensationInfoP
entries (2?.google.cloud.talent.v4beta1.CompensationInfo.CompensationEntryp
"annualized_base_compensation_range (2?.google.cloud.talent.v4beta1.CompensationInfo.CompensationRangeB�Aq
#annualized_total_compensation_range (2?.google.cloud.talent.v4beta1.CompensationInfo.CompensationRangeB�A�
CompensationEntryL
type (2>.google.cloud.talent.v4beta1.CompensationInfo.CompensationTypeL
unit (2>.google.cloud.talent.v4beta1.CompensationInfo.CompensationUnit$
amount (2.google.type.MoneyH P
range (2?.google.cloud.talent.v4beta1.CompensationInfo.CompensationRangeH 
description (	=
expected_units_per_year (2.google.protobuf.DoubleValueB
compensation_amounto
CompensationRange,
max_compensation (2.google.type.Money,
min_compensation (2.google.type.Money"�
CompensationType!
COMPENSATION_TYPE_UNSPECIFIED 
BASE	
BONUS
SIGNING_BONUS

EQUITY
PROFIT_SHARING
COMMISSIONS
TIPS
OTHER_COMPENSATION_TYPE"�
CompensationUnit!
COMPENSATION_UNIT_UNSPECIFIED 

HOURLY	
DAILY

WEEKLY
MONTHLY

YEARLY
ONE_TIME
OTHER_COMPENSATION_UNIT"�
Certification
display_name (	\'
acquire_date (2.google.type.Date&
expire_date (2.google.type.Date
	authority (	
description (	"�
Skill
display_name (	)
last_used_date (2.google.type.DateA
level (22.google.cloud.talent.v4beta1.SkillProficiencyLevel
context (	
skill_name_snippet (	B�A"|
	Interview3
rating (2#.google.cloud.talent.v4beta1.Rating:
outcome (2$.google.cloud.talent.v4beta1.OutcomeB�A"E
Rating
overall (
min (
max (
interval ("�
BatchOperationMetadataH
state (29.google.cloud.talent.v4beta1.BatchOperationMetadata.State
state_description (	
success_count (
failure_count (
total_count (/
create_time (2.google.protobuf.Timestamp/
update_time (2.google.protobuf.Timestamp,
end_time (2.google.protobuf.Timestamp"z
State
STATE_UNSPECIFIED 
INITIALIZING

PROCESSING
	SUCCEEDED

FAILED

CANCELLING
	CANCELLED*y
CompanySize
COMPANY_SIZE_UNSPECIFIED 
MINI	
SMALL
SMEDIUM

MEDIUM
BIG

BIGGER	
GIANT*�

JobBenefit
JOB_BENEFIT_UNSPECIFIED 

CHILD_CARE

DENTAL
DOMESTIC_PARTNER
FLEXIBLE_HOURS
MEDICAL
LIFE_INSURANCE
PARENTAL_LEAVE
RETIREMENT_PLAN
	SICK_DAYS	
VACATION


VISION*�

DegreeType
DEGREE_TYPE_UNSPECIFIED 
PRIMARY_EDUCATION
LOWER_SECONDARY_EDUCATION
UPPER_SECONDARY_EDUCATION
ADULT_REMEDIAL_EDUCATION
ASSOCIATES_OR_EQUIVALENT
BACHELORS_OR_EQUIVALENT
MASTERS_OR_EQUIVALENT
DOCTORAL_OR_EQUIVALENT*�
EmploymentType
EMPLOYMENT_TYPE_UNSPECIFIED 
	FULL_TIME
	PART_TIME

CONTRACTOR
CONTRACT_TO_HIRE
	TEMPORARY

INTERN
	VOLUNTEER
PER_DIEM
FLY_IN_FLY_OUT	
OTHER_EMPLOYMENT_TYPE
*q
JobLevel
JOB_LEVEL_UNSPECIFIED 
ENTRY_LEVEL
EXPERIENCED
MANAGER
DIRECTOR
	EXECUTIVE*�
JobCategory
JOB_CATEGORY_UNSPECIFIED 
ACCOUNTING_AND_FINANCE
ADMINISTRATIVE_AND_OFFICE
ADVERTISING_AND_MARKETING
ANIMAL_CARE
ART_FASHION_AND_DESIGN
BUSINESS_OPERATIONS
CLEANING_AND_FACILITIES
COMPUTER_AND_IT
CONSTRUCTION	
CUSTOMER_SERVICE

	EDUCATION
ENTERTAINMENT_AND_TRAVEL
FARMING_AND_OUTDOORS

HEALTHCARE
HUMAN_RESOURCES\'
#INSTALLATION_MAINTENANCE_AND_REPAIR	
LEGAL

MANAGEMENT
MANUFACTURING_AND_WAREHOUSE$
 MEDIA_COMMUNICATIONS_AND_WRITING
OIL_GAS_AND_MINING
PERSONAL_CARE_AND_SERVICES
PROTECTIVE_SERVICES
REAL_ESTATE
RESTAURANT_AND_HOSPITALITY
SALES_AND_RETAIL
SCIENCE_AND_ENGINEERING"
SOCIAL_SERVICES_AND_NON_PROFIT!
SPORTS_FITNESS_AND_RECREATION 
TRANSPORTATION_AND_LOGISTICS*e
PostingRegion
POSTING_REGION_UNSPECIFIED 
ADMINISTRATIVE_AREA

NATION
TELECOMMUTE*n

Visibility
VISIBILITY_UNSPECIFIED 
ACCOUNT_ONLY
SHARED_WITH_GOOGLE
SHARED_WITH_PUBLIC*Z
ContactInfoUsage"
CONTACT_INFO_USAGE_UNSPECIFIED 
PERSONAL
WORK

SCHOOL*q
HtmlSanitization!
HTML_SANITIZATION_UNSPECIFIED 
HTML_SANITIZATION_DISABLED
SIMPLE_FORMATTING_ONLY*c
CommuteMethod
COMMUTE_METHOD_UNSPECIFIED 
DRIVING
TRANSIT
WALKING
CYCLING*�
SkillProficiencyLevel\'
#SKILL_PROFICIENCY_LEVEL_UNSPECIFIED 
	UNSKILLED
FUNDAMENTAL_AWARENESS

NOVICE
INTERMEDIATE
ADVANCED

EXPERT*f
Outcome
OUTCOME_UNSPECIFIED 
POSITIVE
NEUTRAL
NEGATIVE
OUTCOME_NOT_AVAILABLE*�
AvailabilitySignalType(
$AVAILABILITY_SIGNAL_TYPE_UNSPECIFIED 
JOB_APPLICATION
RESUME_UPDATE
CANDIDATE_UPDATE
CLIENT_SUBMISSIONBy
com.google.cloud.talent.v4beta1BCommonProtoPZAgoogle.golang.org/genproto/googleapis/cloud/talent/v4beta1;talent�CTSbproto3'
        , true);

        static::$is_initialized = true;
    }
}

