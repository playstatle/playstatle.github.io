curl -XPOST \
-H "Authorization: Bearer YOUR_CHANNEL_ACCESS_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "view": {
        "type": "SIZE_OF_LIFF",
        "url": "URL_OF_YOUR_APPLICATION"
    }
}' \
https://api.line.me/liff/v1/apps