# pulsor
Web app health monitoring

## use

> Create file pulsor.json in $_SERVER['DOCUMENT_ROOT']

contents:

``
{
    "app" : {
        "url" : "https://example.com",
        "checkpoints" : [
            "class_of_header_element",
            "class_of_content_element",
            "class_of_footer_element"
        ]
    }
}
``