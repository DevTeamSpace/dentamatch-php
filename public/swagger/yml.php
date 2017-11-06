# this is an example of the Uber API
# as a demonstration of an API spec in YAML
swagger: '2.0'
info:
  title: DentaMatch APIs
  description: Move your app forward with the DentaMatch APIs
  version: "1.0.0"
# the domain of the service
host: <?php echo $_SERVER['HTTP_HOST']; ?>/api
# array of all schemes that your API supports
schemes:
  - http
# will be prefixed to all paths
basePath: /
produces:
  - application/json
paths:
  /users/work-experience-save:
    post:
      summary: Work Experience
      responses: 
          200:
            description: List of added work experience 
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      description: Api to add or edit Work Experience
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: jobTitleId
          in: formData
          description: Job title id
          type: integer
        - name: monthsOfExpereince
          in: formData
          description: No of months of experience
          type: integer
        - name: officeName
          in: formData
          description: Office Name
          type: string
        - name: officeAddress
          in: formData
          description: Office Name
          type: string
        - name: city
          in: formData
          description: City Name
          type: string
        - name: reference1Name
          in: formData
          description: Reference User 1 (Optional)
          type: string
        - name: reference1Mobile
          in: formData
          description: Reference User 1 mobile no (Optional)
          type: string
        - name: reference1Email
          in: formData
          description: Reference User 1 email id (Optional)
          type: string
        - name: reference2Name
          in: formData
          description: Reference User 2 (Optional)
          type: string
        - name: reference2Mobile
          in: formData
          description: Reference User 2 mobile no (Optional)
          type: string
        - name: reference2Email
          in: formData
          description: Reference User 2 email id (Optional)
          type: string
        - name: action
          in: formData
          description: Value as add or edit
          type: string
        - name: id
          in: formData
          description: this is required when action is edit
          type: integer
        
      tags:
        - Work Experience
            
  /users/work-experience-list:
    post:
      summary: Work Experience
      description: Api to list work experience based on access token
      responses: 
          200:
            description: List of added work experience 
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: start
          in: formData
          description: starting point for pagination (Optional)
          type: string
        - name: limit
          in: formData
          description: limit for number of records (Optional)
          type: string
          
      tags:
        - Work Experience
        
  /users/work-experience-delete:
    delete:
      summary: Work Experience
      description: Api to delete work experience based on access token and id
      responses: 
          200:
            description: List of added work experience 
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          required: true
          description: access token
          type: string
        - name: id
          in: formData
          required: true
          description: work experience id
          type: integer
          
      tags:
        - Work Experience  
        
  /users/sign-up:
    post:
      summary: User Onboarding
      description: Api to list work experience based on access token
      responses: 
          200:
            description: User signup process
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: deviceId
          in: formData
          required: true
          description: device  id
          type: string
        - name: deviceType
          in: formData
          required: true
          description: Type of device (ex. iOS or Android)
          type: string
        - name: deviceToken
          in: formData
          description: Token for that particular devide(Optional)
          type: string
        - name: firstName
          in: formData
          required: true
          description: Firstname of the user
          type: string
        - name: lastName
          in: formData
          required: true
          description: Firstname of the user
          type: string
        - name: email
          in: formData
          required: true
          description: Email  of the user
          type: string
        - name: password
          in: formData
          required: true
          description: Password  of the user
          type: string
        - name: preferredJobLocationId
          in: formData
          required: true
          description: preferred Job Location Id
          type: integer
      tags:
        - Users onboarding
        
  /users/sign-in:
    post:
      summary: User Onboarding
      description: Api to list work experience based on access token
      responses: 
          200:
            description: User sign in  process
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: deviceId
          in: formData
          description: device  id
          type: string
        - name: deviceType
          in: formData
          description: Type of device (ex. iOS or Android)
          type: string
        - name: deviceToken
          in: formData
          description: Token for that particular devide(Optional)
          type: string
        - name: email
          in: formData
          description: Email  of the user
          type: string
        - name: password
          in: formData
          description: Password  of the user
          type: string  
      tags:
        - Users onboarding
  /users/forgot-password:
    put:
      summary: User Onboarding
      description: Api for forgot password
      responses: 
          200:
            description: Forgot password  process
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: email
          in: formData
          description: Email  of the user
          type: string
        
      tags:
        - Users onboarding
        
        
  /list-skills:
    get:
      summary: Skill Apis
      description: Api to list skills based on access token
      responses: 
          200:
            description: Skill list
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          required: true
          description: access token
          type: string
      tags:
        - User Skills
        
  /users/update-skill:
    post:
      summary: Update Skill Apis
      description: Api to update skills  based on access token
      responses: 
          200:
            description: Skill Update
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          required: true
          description: access token
          type: string
        - name: skills
          in: formData
          description: provide skill ids in json array ex ("skills":[1,2,3,6])
          type: string
        - name: other
          in: formData
          description: provide other skill ids  with values json array ex ("other":[{"id":1,"value":"abc"}])
          type: string
      tags:
        - User Skills
  /list-certifications:
    get:
      summary: Certification listing 
      description: Api for  listing certificatopn 
      responses: 
          200:
            description: Certification list
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
      parameters:
        - name: accessToken
          in: header
          required: true
          description: access token
          type: string     
     
      tags:
        - User Certification
  /users/update-certificate:
    post:
      summary: Certification listing 
      description: Api for  updating certificatopn 
      responses: 
          200:
            description: Certification update
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
      parameters:
        - name: accessToken
          in: header
          required: true
          description: access token
          type: string
        - name: certificateId
          in: formData
          required: true
          description: Certificate id 
          type: integer 
        - name: image
          in: formData
          required: true
          description: Image to be updated 
          type: file 
     
      tags:
        - User Certification
        
  /users/update-certificate-validity:
    post:
      summary: Certification listing 
      description: Api for  updating certification validity 
      responses: 
          200:
            description: Certification update
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
      parameters:
        - name: accessToken
          in: header
          required: true
          description: access token
          type: string
        - name: certificateValidition
          in: formData
          required: true
          description: Certificate id with values json array ex ([{"id":1,"value":"abc"}])
          type: integer 
      tags:
        - User Certification
        
  /users/school-list:
    get:
      summary: School List
      description: Api to list School data for a user
      responses: 
          200:
            description: Api to list Schooling data for a user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
          
      tags:
        - Schooling
        
  users/school-add:
    post:
      summary: School List
      description: Api to add School data for a user
      responses: 
          200:
            description: Api to add Schoo data for a user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: schoolDataArray
          in: formData
          description: school selected data as array ex(schoolDataArray:[{"schoolingChildId":1,"otherSchooling":"", "yearOfGraduation":2004}]) 
          type: array
          
      tags:
        - Schooling

  /users/affiliation-list:
    get:
      summary: Affiliation List
      description: Api to list Affiliation data for a user
      responses: 
          200:
            description: Api to list Affiliation data for a user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
          
      tags:
        - Affiliations
        
  /users/affiliation-save:
    post:
      summary: Affiliation Save Edit
      description: Api to add and edit Affiliation data for a user
      responses: 
          200:
            description: Api to add and edit Affiliation data for a user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: affiliationDataArray
          in: formData
          description: affiliation selected data as array
          type: array
        - name: other
          in: formData
          description: other selected data as array with text
          type: array
          
      tags:
        - Affiliations
        
  /users/about-me-save:
    post:
      summary: About Me
      description: Api to add about me
      responses: 
          200:
            description: Api to add about me
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: aboutMe
          in: formData
          description: About Me string
          type: string
          
      tags:
        - User Profile
        
  /users/about-me-list:
    get:
      summary: About Me
      description: Api to fetch About Me
      responses: 
          200:
            description: Api to fetch About Me
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
          
      tags:
        - User Profile
        
  /users/user-profile:
    get:
      summary: User Profile
      description: Api to fetch User Profile Data
      responses: 
          200:
            description: Api to fetch User Profile Data
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
          
      tags:
        - User Profile
        
  /users/sign-out:
    delete:
      summary: Users Onboarding
      description: Api to log out user
      responses: 
          200:
            description: Api to log out user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
          
      tags:
        - Users onboarding
        
  /users/change-password:
    post:
      summary: Change user Password
      description: Api to change password
      responses: 
          200:
            description: Api to change user password
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string

        - name: oldPassword
          in: formData
          description: old password for user
          type: string
        - name: newPassword
          in: formData
          description: new password for user
          type: string
        - name: confirmNewPassword
          in: formData
          description: confirm  password for user
          type: string
          
      tags:
        - User Profile
        
  /users/update-license:
    post:
      summary: Update lisence , state and jobtitle of user
      description: Api to update lisence , state and jobtitle
      responses: 
          200:
            description: Api to update lisence , state and jobtitle
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: jobTitleId
          in: formData
          description: jobtitle for user (optional) , leave blank for update
          type: integer
        - name: license
          in: formData
          description: license for user (required if jobTitleId criteria validates it) , leave blank for update
          type: string
        - name: state
          in: formData
          description: state for user (required if license is not null) , leave blank for update
          type: string
        - name: aboutMe
          in: formData
          description: about me
          type: string
      tags:
        - User Profile
        
  /users/user-location-update:
    post:
      summary: Update home location of user
      description: Update home location of user
      responses: 
          200:
            description: Api to update lisence , state and jobtitle
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: preferedLocation
          in: formData
          description: user preffered location
          type: string
        - name: latitude
          in: formData
          description: user latitude
          type: string
        - name: longitude
          in: formData
          description: user longitude
          type: string
        - name: zipCode
          in: formData
          description: Zipcode of user location
          type: integers
          
      tags:
        - User Profile
        
  /users/update-availability:
    post:
      summary: Update user availability
      description: Api to update user availability 
      responses: 
          200:
            description: Api to update user availability
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: isFulltime
          in: formData
          description: value will be 1 if user is available for full time and 0 if user is not available fulltime.
          type: integers
        - name: partTimeDays
          in: formData
          description: provide days for which user is available in array ex ([monday,tuesday])
          type: array
        - name: tempdDates
          in: formData
          description: provide date for which user is available in array ex ([2107-01-20,2107-01-20])
          type: array
        
          
      tags:
        - Availability
        
  /users/user-profile-update:
    put:
      summary: Update user profile
      description: Api to update user availability 
      responses: 
          200:
            description: Api to update user availability
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: firstName
          in: formData
          description: First Name
          type: string
        - name: lastName
          in: formData
          description: Last Name
          type: string
        - name: zipcode
          in: formData
          description: Zipcode
          type: integer
        - name: latitude
          in: formData
          description: Latitude
          type: string
        - name: longitude
          in: formData
          description: Longitude
          type: string
        - name: preferredJobLocation
          in: formData
          description: Preffered job location
          type: string
        - name: jobTitileId
          in: formData
          description: Job title id
          type: integer
        - name: aboutMe
          in: formData
          description: About me
          type: string
          
      tags:

        - User Profile
        
  /users/search-jobs:
    post:
      summary: Search jobs by user according to parameters
      description: Api to search jobs
      responses: 
          200:
            description: Api to search jobs
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: lat
          in: formData
          description: latitude provided by user
          type: string
        - name: lng
          in: formData
          description: longitude provided by user
          type: string
        - name: zipCode
          in: formData
          description: zipcode provided by user
          type: string
        - name: page
          in: formData
          description: Page number
          type: numeric
        - name: jobTitle
          in: formData
          description: send job title id in array that you want to search (ex.[1,2,3])
          type: string
        - name: isFulltime
          in: formData
          description: Send 1 if user is available for full time
          type: numerical
        - name: isParttime
          in: formData
          description: Send 1 if user is available for part time
          type: numeric
        - name: parttimeDays
          in: formData
          description: send days in array (ex. [sunday,monday,tuesday])
          type: string
          
      tags:
        - Job search
        
  /users/save-job:
    post:
      summary: Save unsave job by user
      description: Api to save unsave jobs
      responses: 
          200:
            description: Api to save unsave jobs
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: jobId
          in: formData
          description: jobid provided by user
          type: integer
        - name: status
          in: formData
          description: status is 1 for saving job and 0 to unsave job
          type: integer
          
      tags:
        - Job search
          
  /users/apply-job:
    post:
      summary: Apply  job by user
      description: Api to apply job
      responses: 
          200:
            description: Api to apply jobs
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: jobId
          in: formData
          description: jobid provided by user
          type: integer 
      tags:
        - Job search
     
  /users/cancel-job:
    post:
      summary: Cancel applied   job by user
      description: Api to cancel applied job by user
      responses: 
          200:
            description: Cancel applied   job by user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: jobId
          in: formData
          description: jobid provided by user
          type: integer
        - name: cancelReason
          in: formData
          description: Reason for cancel job 
          type: string 
      tags:
        - Job search
        
  /users/job-list:
    get:
      summary: Get job list for saved jobs , applied jobs and cancelled jobs by user
      description: Api to get list of save , applied and cancelled jobs
      responses: 
          200:
            description: Api to get list of save , applied and cancelled jobs
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: type
          in: formData
          description: type = 1 for saved jobs , 2 for applied jobs and 3 for cancelled jobs
          type: integer
        - name: page
          in: formData
          description: send page number to show result
          type: integer
        - name: lat
          in: formData
          description: latitude of current user location
          type: integer
        - name: lng
          in: formData
          description: longitude of current user location
          type: integer  
      tags:
        - Job search
        
  /jobs/job-detail:
    post:
      summary: Get Job Detail
      description: Api to job detail
      responses: 
          200:
            description: Api to get job detail
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: jobId
          in: formData
          description: job id of job
          type: integer
         
      tags:
        - Job search
        
  /jobs/hired-jobs:
    post:
      summary: Get Hired Jobs
      description: Api to Hired Jobs
      responses: 
          200:
            description: Api to get Hired Jobs
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: jobStartDate
          in: formData
          description: jobStartDate in YYYY-MM-DD format
          type: string
        - name: jobEndDate
          in: formData
          description: jobEndDate in YYYY-MM-DD format
          type: string
         
      tags:
        - Job search

  /users/availability-list:
    post:
      summary: Get Hired Jobs
      description: Api to get availability of user
      responses: 
          200:
            description: Api to get availability of user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: calendarStartDate
          in: formData
          description: calendarStartDate in YYYY-MM-DD format
          type: string
        - name: calendarEndDate
          in: formData
          description: calendarEndDate in YYYY-MM-DD format
          type: string
         
      tags:
        - Availability
        
  /users/chat-user-list:
    get:
      summary: Get Chat user list
      description: Api to get chat user list
      responses: 
          200:
            description: Api to get chat user list
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
  
      tags:
        - Chat
        
   
  /users/chat-user-block-unblock:
    post:
      summary: Block unblock recruiter
      description: Block unblock recruiter
      responses: 
          200:
            description: Api to block unblock recruiter
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: recruiterId
          in: formData
          description: recruiter id
          type: integer
  
      tags:
        - Chat
        
  /users/notification-list:
    get:
      summary: Notification listing
      description: User notification list
      responses: 
          200:
            description: User notification list
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: page
          in: formData
          description: recruiter id
          type: integer
  
      tags:
        - Notification
        
        
  /users/notification-read:
    post:
      summary: Read notification
      description: Read Notification
      responses: 
          200:
            description: Read notification list
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: notificationId
          in: formData
          description: notificationId 
          type: integer
  
      tags:
        - Notification
        
        
  /users/update-devicetoken:
    post:
      summary: Update device token of user
      description: Update device token of user
      responses: 
          200:
            description: Update device token of user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: updateDeviceToken
          in: formData
          description: updateDeviceToken 
          type: string
  
      tags:
        - User Profile
        
        
  /users/acceptreject-job:
    post:
      summary: Accept reject jobs by user
      description: Accept reject jobs by user
      responses: 
          200:
            description: Accept reject jobs by user
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
             
      parameters:
        - name: accessToken
          in: header
          description: access token
          type: string
        - name: notificationId
          in: formData
          description: updateDeviceToken 
          type: integer
        - name: acceptStatus
          in: formData
          description: accept status (0 = reject ; 1 = select) 
          type: integer  
        
  
      tags:
        - Job search
        
  /jobs/preferred-job-locations:
    get:
      summary: Preferred Job Locations
      description: Api to list preferred job locations
      responses: 
          200:
            description: Preferred Job Locations
          default:
            description: Unexpected error
            schema:
             $ref: '#/definitions/Error'
      tags:
        - Preferred Job Locations


