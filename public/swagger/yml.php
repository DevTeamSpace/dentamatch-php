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
          description: Type of device (ex. iOS or Android)
          type: string
        - name: deviceToken
          in: formData
          description: Token for that particular devide(Optional)
          type: string
        - name: firstName
          in: formData
          description: Firstname of the user
          type: string
        - name: email
          in: formData
          description: Email  of the user
          type: string
        - name: password
          in: formData
          description: Password  of the user
          type: string
        - name: preferedLocation
          in: formData
          description: Preffered location of the user
          type: string
        - name: latitude
          in: formData
          description: Latitude of user location
          type: string
        - name: longitude
          in: formData
          description: Longitude of user location
          type: string
        - name: zipCode
          in: formData
          description: Zipcode  of user location
          type: string
          
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
          description: school selected data as array
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

        - name: license
          in: formData
          description: license for user
          type: string
        - name: state
          in: formData
          description: state for user
          type: string
        - name: jobTitleId
          in: formData
          description: jobtitle for user (optional) , leave blank for update
          type: string
          
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

        - Availability
        
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


