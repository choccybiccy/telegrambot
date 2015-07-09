# Telegram Bot API
[![Build Status](https://travis-ci.org/choccybiccy/telegrambot.svg?branch=master)](https://travis-ci.org/choccybiccy/telegrambot) 
[![Latest Stable Version](https://poser.pugx.org/choccybiccy/telegrambot/v/stable.svg)](https://packagist.org/packages/choccybiccy/telegrambot) 
[![Latest Unstable Version](https://poser.pugx.org/phpunit/phpunit/v/unstable)](//packagist.org/packages/phpunit/phpunit)
[![License](https://poser.pugx.org/choccybiccy/telegrambot/license.svg)](https://packagist.org/packages/choccybiccy/telegrambot)

Build better Telegram bots.

## Introduction
This is a PHP library to help easily build your Telegram bot and it's commands.

## Installation

    composer require choccybiccy/telegrambot

## Usage

### Creating commands
Let's assume your project is structured like this, and your `src/` folder is namespaced as `App`:

    public/
        index.php
    src/
        Controller/
        Entity/
        Telegram/
            Command/

Let's go ahead and create a file called `ExampleCommand.php` in `src/Telegram/Command/`. This command will simply
echo whatever the person issuing the command passed as an argument to the command. The user will issue the following
command:

    /example Hello World

Now let's create that file:

    <?php

    namespace App;

    use Choccybiccy\Telegram\Command;
    use Choccybiccy\Telegram\Entity\Message;

    class ExampleCommand extends Command
    {

        /**
         * Execute the command
         *
         * @param string $argument The arguments passed to the command
         * @param Message $message The message object issuing the command
         */
        protected function execute($argument, Message $message)
        {

        }
    }

As you can see, the argument and the Message object is passed to the `execute($argument, Message $message)` method,
which gives us everything we need to know about the command that was issued, who issued it and where.

Using the `\Choccybiccy\Telegram\ApiClient` provided within the abstract `Command` class we can send a message back to
the private or group chat. All we need is the chat ID from the private or group chat:

    <?php

    namespace App;

    use Choccybiccy\Telegram\Command;
    use Choccybiccy\Telegram\Entity\Message;

    class ExampleCommand extends Command
    {

        /**
         * Execute the command
         *
         * @param string $argument The arguments passed to the command
         * @param Message $message The message object issuing the command
         */
        protected function execute($argument, Message $message)
        {

            $chat = $message->chat;
            $chatId = $chat->id;

        }
    }

Now we have the chat ID, let's send a message back to the chat that simply says whatever the argument was for our
command. In our example our command by the user was `/example Hello World`, so our argument will be `Hello World`. Let's
go ahead and send a message:

    <?php

    namespace App;

    use Choccybiccy\Telegram\ApiClient;
    use Choccybiccy\Telegram\Command;
    use Choccybiccy\Telegram\Entity\Message;

    class ExampleCommand extends Command
    {

        /**
         * Execute the command
         *
         * @param string $argument The arguments passed to the command
         * @param Message $message The message object issuing the command
         */
        protected function execute($argument, Message $message)
        {

            $chat = $message->chat;
            $chatId = $chat->id;

            /** @var ApiClient $client */
            $client = $this->getApiClient();

            /** @var Message $outgoingMessage */
            $outgoingMessage = $client->sendMessage($chatId, $argument);

        }
    }

The return from the `sendMessage` method is a `Message` object from the message we just sent.

### The CommandHandler
All our commands are registered in a `CommandHandler`. This is responsible for grouping all our commands together so
all we have to do is provide it an `Update` object, and let the `CommandHandler` work out what command was issued
and execute where appropriate.

We'll use the very easy and lightweight [Flight](https://github.com/mikecao/flight/) router as a very quick example
of building our bot web-service. In our example application structure, we're going to setup our `public/index.php`
ready to add our commands.

    <?php

    namespace App;

    require_once "../vendor/autoload.php";

    use Choccybiccy\Telegram\ApiClient;
    use Choccybiccy\Telegram\CommandHandler;
    use Flight;
    use Symfony\Component\HttpFoundation\Request;

    $apiClient = new ApiClient("<bot-authorisation-token>");
    $request = Request::createFromGlobals();

    Flight::route("/webhook", function () use ($request, $apiClient) {

        $commands = new CommandHandler($apiClient);

    });

Our `CommandHandler` is ready to register commands. Now we're going to add our command, and pass the command trigger
as part of the command constructor. In this example our command will be triggered when somebody types `/example`, so
we'll pass `example` to the command constructor. Then we'll construct an Telegram `Update` object from the request body
sent by the Telegram API to our awaiting webhook.

    <?php

    namespace App;

    require_once "../vendor/autoload.php";

    use App\Telegram\Command\ExampleCommand;
    use Choccybiccy\Telegram\ApiClient;
    use Choccybiccy\Telegram\CommandHandler;
    use Choccybiccy\Telegram\Entity\Update;
    use Flight;
    use Symfony\Component\HttpFoundation\Request;

    $apiClient = new ApiClient("<bot-authorisation-token>");
    $request = Request::createFromGlobals();

    Flight::route("/webhook", function () use ($request, $apiClient) {

        $commands = new CommandHandler($apiClient);

        $commands->register(new ExampleCommand("example"));

        /** @var Update $update */
        $update = $apiClient->entityFromBody($request->getContent(), new Update());

        $commands->run($update);

    });

And that's it. When the Telegram API hits our webservice at `/hook`, we convert the request body into an `Update` object
and pass this over to our `CommandHandler`. The handler then grabs the message text the user sent and matches it up
to a command registered within it. When it matched `/example` up our `ExampleCommand` command, it then executes it. Our
example command simply replies back to the private or group chat with whatever the argument to the `/example` command
was.

### Interacting with the Bot API
All the available methods outlined in the [Telegram Bot API](https://core.telegram.org/bots/api#available-methods) are
supported by this library. Here's a few examples:

#### sendMessage

    $apiClient = new ApiClient("<authorisation token>");
    $message = $apiClient->sendMessage($chatId, "My example message");

#### sendPhoto

    $photo = new \Choccybiccy\Telegram\Entity\InputFile(file_get_contents("photo.jpg"));

    $apiClient = new ApiClient("<authorisation token>");
    $message = $apiClient->sendPhoto($chatId, $photo, "Cool photo, huh?");

# Further reading
For more information regarding the Telegram API, check out these resources:

* [Introduction to bots](https://core.telegram.org/bots)
* [Creating bots/BotFather](https://core.telegram.org/bots#botfather)
* [Available types](https://core.telegram.org/bots/api#available-types)
* [Available methods](https://core.telegram.org/bots/api#available-methods)