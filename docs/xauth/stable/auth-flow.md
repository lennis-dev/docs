# Auth Flow

This document describes the authentication flow for the xAuth API.

## Paticipants

- **Client**: The application that wants to access the xAuth API.
    - Has an Callback URL.
    - Has the ability to redirect users to the xAuth API.

- **xAuth API**: The API that the client wants to access.
    - Has the ability to authenticate users.
    - Has the ability to redirect users back to the client.
    
- **User**: The person who is using the client application.
    - Has an account with the xAuth API.


## Flow

1. The **Client** redirects the user to the xAuth API.
    - The **Client** includes the Application Name, the Requested Scopes, the Callback URL, and maybe an Icon URL.
2. The xAuth API authenticates the user.
    - The **xAuth API** may prompt the **User** to login with their credentials.
        - The **xAuth API** may prompt the **User** to create an account.
    - The **xAuth API** prompt the **User** to authorize the **Client**.
3. The **xAuth API** redirects the **User** back to the **Client**.
    - The **xAuth API** includes an Token.
4. The **Client** uses the Token to access the xAuth API.
    - The **Client** can now access the xAuth API. Eg. use the Token to make a [Identify Request](./api/identify).