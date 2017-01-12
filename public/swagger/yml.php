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
          description: Reference User 1 mobile no (Required when key reference1Name is filled)
          type: string
        - name: reference1Email
          in: formData
          description: Reference User 1 email id (Required when key reference1Name is filled)
          type: string
        - name: reference2Name
          in: formData
          description: Reference User 2 (Optional)
          type: string
        - name: reference2Mobile
          in: formData
          description: Reference User 2 mobile no (Required when key reference2Name is filled)
          type: string
        - name: reference2Email
          in: formData
          description: Reference User 2 email id (Required when key reference2Name is filled)
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
        
  /users/signIn:
    post:
      summary: Work Experienceaaa
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
          required: true
          description: access token
          type: string
        - name: id
          in: formData
          required: true
          description: work experience id
          type: integer
        - name: start
          in: formData
          description: starting point for pagination (Optional)
          type: string
        - name: limit
          in: formData
          description: limit for number of records (Optional)
          type: string
          
      tags:
        - Users
        
      