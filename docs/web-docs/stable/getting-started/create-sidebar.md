# Create a sidebar

The sidebar is a file that contains the structure of your documentation. It is used to generate the sidebar of your documentation. The sidebar file is a JSON file that contains the structure of your documentation. The structure of the sidebar file is as follows:

```json
[
    {
        "label": "item 1",
        "link": "item-1",
        "type": "item"
    },
    {
        "label": "item 2",
        "link": "item-2",
        "type": "item"
    },
   {
    "label": "Category 1",
    "link": "category-1",
    "type": "category",
    "items": [
        {
            "label": "item 3",
            "link": "item-3",
            "type": "item"
        },
        {
            "label": "item 4",
            "link": "item-4",
            "type": "item"
        }
    ]
  }
]
```

The sidebar file is a JSON array that contains objects. Each object represents an item or a category in the sidebar. The object has the following properties:
 * `label`: The label of the item or category.
 * `link`: The link of the item or category.
 * `type`: The type of the item or category. It can be `item` or `category`.
 * `items`: The items of the category. It is an array of objects that have the same properties as the parent object.