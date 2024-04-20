# Identify

```url
POST /api/v1/identify
```

Identify a user by a Token.

## Request

### Body

- `token` (string): The Token to identify the user.

## Response

- `success` (boolean): Whether the request was successful.
- `error` (string): The error message if the request was not successful.
- `data` (object): The data of the user.
    - `username` (string): The username of the Token.
    - `application` (string): The application of the Token.
    - `scopes` (array): The scopes of the Token.
    - `server` (string): The xAuth server of the Token.