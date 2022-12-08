<?php

/**@apiDefine  auth_token
 *
 * @apiParam {String} auth_token="sytMwXCdvUwfqJUIjRhrU0RzKibmZa" The auth token is the most commonly used type of token.
 *                                                                  This type of token is needed any time the app calls an API to read, modify or write data.
 */

/**@apiDefine  device_id
 *
 * @apiParam {String} device_id="be74e7f8f645db6eddd" The Device Id passed from user's device.
 */

/**@apiDefine  device_token
 *
 * @apiParam {String} device_token="dfbasde74e7f8f645db6eddd74e7f8f645db6edasd" The Device Token passed from user's device.
 */

/**@apiDefine  device_type
 *
 * @apiParam {String} device_type="android" The type of device the user has.
 */

/**@apiDefine  device_model
 *
 * @apiParam {String} device_model="Xiaomi Redmi 5" The Model name of device the user has.
 */

/**@apiDefine  device_version
 *
 * @apiParam {String} device_version="25" The Version number of device type the user has.
 */

/**@apiDefine  language_id
 *
 * @apiParam {int} language_id="1" The Language Id passed from user's device.
 */
/**@apiDefine  member_detail_id
 *
 * @apiParam {int} member_detail_id="1" The member Id passed from user's device.
 */

/**@apiDefine  question_id
 *
 * @apiParam {int} question_id = "1" The Question Id passed from user's device.
 */

/**@apiDefine  like_status
 *
 * @apiParam {int} like_status = "1" The Like Status passed from user's device.(Like or Unlike)
 */

/**@apiDefine  fav_status
 *
 * @apiParam {int} fav_status = "1" The Like Status passed from user's device.(Favourite or UnFavourite)
 */

/**@apiDefine  category_id
 *
 * @apiParam {int} category_id="1" The Category Id passed from user's device.
 */

/**@apiDefine  start
 *
 * @apiParam {int} start="0"  The input number for paging purpose.
 *
 */
/**@apiDefine  post_id
 *
 * @apiParam {int} post_id="1"  The Post Id  for Deleting Post.
 *
 */
/**@apiDefine  business_id
 *
 * @apiParam {int} business_id="1"  The Business Id  for Deleting Business.
 *
 */
/**@apiDefine  mobile
 *
 * @apiParam {int} mobile="999999999"  Mobile Number for login.
 *
 */
/**@apiDefine  otp
 *
 * @apiParam {int} otp="123456"  One Time Password  for login.
 *
 */
/**@apiDefine  member_id
 *
 * @apiParam {int} member_id="1"  Member id pass from user device to get data.
 *
 */
/**@apiDefine  business_type_id
 *
 * @apiParam {int} business_type_id="1"  member type id to insert/update business type data.
 *
 */
/**@apiDefine  business_name
 *
 * @apiParam {string} business_type_id="Business Name"  Business detail to get business name from user to insert/update.
 *
 */
/**@apiDefine  owner_name
 *
 * @apiParam {string} owner_name="owner Name"  Owner detail to get Owner name from user to insert/update.
 *
 */
/**@apiDefine  address
 *
 * @apiParam {string} address="Business Address"  Address to get business Address from user to insert/update.
 *
 */
/**@apiDefine  address_geo
 *
 * @apiParam {string} address_geo=" Geo Address"  Address wil get from user through map to insert/update business address.
 *
 */
/**@apiDefine  description
 *
 * @apiParam {string} description="Business Description"  Address wil get from user through map to insert/update business address.
 *
 */
/**@apiDefine  lat
 *
 * @apiParam {string} lat="Address latitude" lat is use to insert or update latitude of your address.
 *
 */
/**@apiDefine  lng
 *
 * @apiParam {string} lng="Address longitude" lng is use to insert or update longitude of your address.
 *
 */
/**@apiDefine  business_mobile
 *
 * @apiParam {int} business_mobile[]="1234567890" Multiple mobile numbers for business to insert or update.
 *
 */
/**@apiDefine  business_email
 *
 * @apiParam {string} business_email[]="Email Address" Multiple Email Addresses for business to insert or update.
 *
 */
/**@apiDefine  email
 *
 * @apiParam {string} email="Email Address"  Email Addresses of Member.
 *
 */
/**@apiDefine  first_name
 *
 * @apiParam {string} first_name="First Name"  First name of Member.
 *
 */
/**@apiDefine  middle_name
 *
 * @apiParam {string} middle_name="Middle Name"  Middle name of Member.
 *
 */
/**@apiDefine  surname
 *
 * @apiParam {string} surname = "Surname"  Surname of Member.
 *
 */
/**@apiDefine  blood_group
 *
 * @apiParam {string} blood_group = "Blood Group"  Blood Group of Member.
 *
 */
/**@apiDefine  marital_status
 *
 * @apiParam {string} marital_status = "Married/Unmarried"  Marital Status of Member.
 *
 */
/**@apiDefine  date_of_birth
 *
 * @apiParam {string} date_of_birth = "00/00/0000"  Date Of Birth of Member.
 *
 */
/**@apiDefine  education
 *
 * @apiParam {string} education = "" Education of Member.
 *
 */
/**@apiDefine  age
 *
 * @apiParam {string} age = "18" Age of Member.
 *
 */
/**@apiDefine  home_town
 *
 * @apiParam {string} home_town = "Home Town" Home Town of Member.
 *
 */
/**@apiDefine  current_work
 *
 * @apiParam {string} current_work = "current_work" Current of Member.
 *
 */
/**@apiDefine  member_mobile
 *
 * @apiParam {string} member_mobile = "1234567890" Mobile number of Member.
 *
 */
/**@apiDefine  member_mobile_type
 *
 * @apiParam {string} member_mobile_type = "Home/office etc." Mobile number type of Member.
 *
 */
/**@apiDefine  business_telephone
 *
 * @apiParam {int} business_telephone[]="0262222222" Multiple Telephone Number for business to insert or update.
 *
 */
/**@apiDefine  limit
 *
 * @apiParam {int} limit="10" limit data the perticular page.
 */

/**@apiDefine  title
 *
 * @apiParam {string} title="Title Post" Title for particular Post.
 */
/**@apiDefine  content
 *
 * @apiParam {string} content="content Post" Content for particular Post.
 */
/**@apiDefine  tags
 *
 * @apiParam {string} tags[]="Tag Post" Tags for particular Post.
 */
/**@apiDefine  is_active
 *
 * @apiParam {int} is_active="1/0" Use to make Post active or inactive.
 */
/**@apiDefine  category_id
 *
 * @apiParam {int} category_id[]="1" category id to insert and update category in post.
 */
/**@apiDefine  other_oil
 *
 * @apiParam {int} other_oil[]="1" Oil id to insert and update Oil in post.
 */

/**@apiDefine  post_image
 *
 * @apiParam {int} post_image[]="image.jpg" Image to Insert or update Image for post.
 */
/**@apiDefine  business_image
 *
 * @apiParam {int} business_image[]="image.jpg" Image to Insert or update Image for Business    .
 */

/**@apiDefine  user_id
 *
 * @apiParam {int} user_id="1" User id use to get users data.
 */
/**@apiDefine  pachkhan_id
 *
 * @apiParam {int} pachkhan_id="1" Pachkhan id use to get pachkhan data.
 */
/**@apiDefine  aadhar_card_no
 *
 * @apiParam {int} aadhar_card_no="123456789123" Adhar card number of member.
 */
/**@apiDefine  member_number
 *
 * @apiParam {String} member_number="A1" Unique number for member.
 */

/**@apiDefine  samaj_id
 *
 * @apiParam {int} samaj_id="1" Samaj id use to get Samaj data.
 */


/**@apiDefine  search
 *
 * @apiParam {String} [search] Search parameter for data filtration
 */
/**@apiDefine  parent_member_id
 *
 * @apiParam {int} [parent_member_id] Parent member id to get Sub members
 */
/**@apiDefine  event_id
 *
 * @apiParam {int} event_id = "1" It is use to get particular Event
 */
/**@apiDefine  is_interested
 *
 * @apiParam {int} is_interested = "1/0" it is use to get interest of member for particular Event
 */
/**@apiDefine  search_parameter
 *
 * @apiParam {String} [search_parameter] Search parameter for data filtration
 */
/**@apiDefine  AuthTokenExpire
 *
 * @apiError  402-AuthTokenExpire  Authtoken Expire
 *
 * @apiErrorExample AuthToken Expire
 * {
 *  "status": false,
 *  "error": "Auth token Expire",
 *  "responseCode": 402,
 * }
 */

 /** @apiDefine InvalidApiKey
 *
 * @apiError 403-InvalidApiKey Invalid API Key
 *
 * @apiErrorExample  Invalid API Key
 * {
 *  "status": false,
 *  "error": "Invalid API key",
 *  "responseCode": 403,
 * }
 */

 /**
 * @apiDefine  InvalidAuthToken
 *
 * @apiError 401-InvalidAuthToken Invalid AuthToken
 *
 * @apiErrorExample  Invalid AuthToken
 * {
 *  "status": false,
 *  "error": "Invalid Token",
 *  "responseCode": 401,
 * }
 */

 /** @apiDefine  UnknownMethod
 *
 * @apiError 405-UnknownMethod Unknown Method
 *
 * @apiErrorExample  Unknown Method
 * {
 *  "status": false,
 *  "error": "Unknown Method",
 *  "responseCode": 405,
 * }
 */
/** @apiDefine  InvalidOtp
 *
 * @apiError 406-"invalid OTP"
 *
 * @apiErrorExample  Invalid OTP
 * {
 *   "status": false,
 *	 "responseCode": 406,
 *	 "message": "invalid OTP",
 *	 "limit": 10,
 *	 "data": null
 * }
 */
/** @apiDefine  ExpireOtp
 *
 * @apiError 407-"OTP Expired"
 *
 * @apiErrorExample  Expired OTP
 * {
 *   "status": false,
 *	 "responseCode": 407,
 *	 "message": "OTP validity has been expired",
 *	 "limit": 10,
 *	 "data": null
 * }
 */
 /**@apiDefine  NoDataFound
 *
 * @apiError 404-NoDataFound No Data Found
 *
 * @apiErrorExample  No Data Found
 * {
 *  "status": false,
 *  "error": "No Data Found",
 *  "responseCode": 404,
 * }
 */

/**@apiDefine  RequiredParameter
 *
 * @apiError 400-RequiredParameter Required Parameter
 *
 * @apiErrorExample  Required Parameter
 * {
 *  "status": false,
 *  "error": "User id is required,Token is required,Start is required,Limit is required",
 *  "responseCode": 400,
 * }
 */


/**@apiDefine  RequiredParameterFilter
 *
 * @apiError 401-RequiredParameterFilter Required Parameter
 *
 * @apiErrorExample  Required Parameter
 * {
 *  "status": false,
 *  "error": "User Id is required,Token is required",
 *  "responseCode": 401,
 * }
 */

/**
 * @apiDefine  SuccessDetails
 *
 * @apiSuccess status           True or False based on successful response of api.
 * @apiSuccess responseCode     Different response code send according to response of api.
 * @apiSuccess message          The relevant message
 * @apiSuccess limit            The number of data to be retrieved
 * @apiSuccess data             return null array or appropriate data in array.
 *
 */
?>
