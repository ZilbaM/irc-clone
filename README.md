# Laravel IRC Clone

This project is a simple clone of IRC (Internet Relay Chat) functionality implemented using Laravel. It allows creating chat rooms and posting messages to them. There is no UI or database involved; chat rooms and messages are managed through text files within the storage system.

## Installation

To install and run this project, you'll need to have PHP and Composer installed on your system.

1. Clone the repository to your local machine:
```sh
git clone https://github.com/ZilbaM/irc-clone
```
2. Navigate to the project directory:
```sh
cd irc-clone
```
3. Install the dependencies:
```sh
composer install
```
4. Start the Laravel development server:
```sh
php artisan serve
```
The application should now be running on `http://localhost:8000`.

## API Endpoints
### Post a Message
**POST `/api/{roomId}`**

This endpoint is used to post a message to a specific chat room.

Parameters:
- **`roomId`**: The ID of the chat room to which you want to post the message (string or int).
- **`author`**: The author's name.
- **`message`**: The message content.

Request:
```sh
curl -X POST http://localhost:8000/api/{roomId} \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "author=Jane&message=Hello World"
```
Response:
```json
{
  "status": "success",
  "message": "Message posted"
}
```

### Get Messages from a Chat Room
**GET `/api/{roomId}`**

This endpoint retrieves messages from a specific chat room.

Parameters:
- **`roomId`**: The ID of the chat room from which you want to retrieve messages(string or int).
Request:
```sh
curl http://localhost:8000/api/{roomId}
```
Response:
```json
{
  "status": "success",
  "messages": [
    {
      "timestamp": "2023-11-06 10:00:00",
      "author": "Jane",
      "message": "Hello World"
    },
    ...
  ]
}
```
If the room does not exist, the response will be:
{
  "status": "error",
  "message": "Room not found"
}

## Notes
All messages are stored in plain text files within the storage/app/rooms directory.
Rooms are automatically created when the first message is posted.
There is no persistence beyond the text files, and no cleanup mechanisms are in place.

## Contributing
Contributions are welcome! Please feel free to submit pull requests or open issues.

## License
This project is open-sourced software licensed under the MIT license.