# Lennis webDocs

> [!WARNING]
> This software is still in development and not ready for production use.

## Introduction

This is a documentation software that allows you to publish your documentation in a simple and easy way. It is based on markdown files and uses Parsedown to parse the markdown files. This Software is written in PHP. 

## Features
 * Easy to use
 * Customizable
 * Lightweight
 * Fast
 * No database required

## Installation

1. Fork this repository and clone it to your server. You can also download the zip file and extract it to your server.

2. Create a new folder in the `docs` folder and name it as you like. (Documentation name e.g. `getting-started`)

3. Create a new folder in the `docs` folder and name it as you like (Version name e.g. `v1.0.0`).

4. create a new file name `data.json` in the version folder and add something like this:
    ```json
    [
        {
            "type": "category",
            "label": "Getting Started",
            "link": "getting-started",
            "items": [
                {
                    "type": "item",
                    "label": "Installation",
                    "link": "installation"
                }
            ]
        },
        {
            "type": "item",
            "label": "Features",
            "link": "features"
        }
    ]
    ```
    This will create your navigation. You can add more categories and items as you like.

5. Now you can create your markdown files in the version folder. You can use the `index.md` file as the main file.

6. You can now access your documentation by going to `http://yourdomain.com/docs/documentation-name/version-name`

## Credits

Big thanks to [@erusev](https://github.com/erusev) for creating the [Parsedown](https://github.com/erusev/parsedown) library. The icons are from [Bootstrap](https://icons.getbootstrap.com/). Also thanks to [@oliveratgithub](https://github.com/oliveratgithub) for creating the [emoji list](https://gist.github.com/oliveratgithub/0bf11a9aff0d6da7b46f1490f86a71eb/d8e4b78cfe66862cf3809443c1dba017f37b61db)

