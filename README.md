# Patienceman | Synca | Notifier

Provide a convenient way to interact with Notifications, including Emails, Dbnotification, and one signal notifications,
it just helps your declaration and interaction easier.

## Installation

Install the package doesn't require much requirement except to use the following command in the laravel terminal,  and you're good to go.

```bash
composer require patienceman/synca
```

> Before get started let first create Queue table and Notifications:
>
> ```bash  
> php artisan queue:table
> php artisan notification:table
> php artisan migrate
> ```
>
> Finally, don't forget to instruct your application to use the database```driver``` by updating the ```QUEUE_CONNECTION``` variable in your application's ```env``` or any queue drive you use, consider [Laravel queue doc](https://laravel.com/docs/8.x/queues) file:
>
> ```php
> QUEUE_CONNECTION=database 
> ```

## Usage

To start working with Noptifier,  u need to run command :tada:
in your custom directories:

```bash
php artisan make:notifier EmailNotification
```

so it will create the filter file for you, Just in **Notifiers** directory

```php
App\Notifiers
```

But in this doc, we'll be using 
>``` App\Notifications ```

```PHP
namespace App\Notifications;

use Patienceman\Synca\NotifyHandler;

class EmailNotification extends NotifyHandler {
    /**
     * Execute notification actions
     * 
     * @return mixed
     */
    public function handle() {
        // do whatever action inside handler
    }
}
```

So you may want even to specify the custom path for your Notifier, Just relax and add it in front of your notifier name.
Let's take again our current example.

```bash
php artisan make:notifier Model/EmailNotification
```

To communicate/use your Notifier, you only need to call Notifier class,
Let take a quick example in our CandidateController class to notify about application between creator and seeker

```PHP
namespace App\Http\Controllers;

use App\Notifications\EmailNotification;
use Patienceman\Synca\Facades\Notifier;

class UsersController extends Controller {

    /**
     * Handle User Notifications
     */
    public function notifications(Notifier $notifier) {
        // ... Other Codes

        $notifier->send([
            EmailNotification::process([ 
                'message' => 'Application sent to job sent' 
            ]),
        ]);
    }

}
```

Now on, we are able send our email notification anytime, any place.
So there is many feature comes with notifier, includes
>```->onQueue()```

>```->to($user1, $user2, ....)```

let take a look:

```PHP
namespace App\Http\Controllers;

use App\Notifications\EmailNotification;
use Patienceman\Synca\Facades\Notifier;

class UsersController extends Controller {
    /**
     * Handle User Notifications
     */
    public function notifications(Notifier $notifier, User $user) {
        // ... Other Codes

        $application = Application::findById('1')->belongsToCompany()->user_id;
        $notification = [ 'message' => 'Application sent to job sent' ];
        
        $users = [
            'user' => $user
            'applicant' => $application
        ];

        $notifier->send([
            EmailNotification::process($notification)
                ->to($users)
                ->onQueue(),
        ]);
    }

}
```

So to access the passed users you need to just call one by one using indexes: for **example**:
with 
>``` ->to($users);```

```PHP
$this->user;
$this->applicant;
```

This is so cool, but there might be a time where you need to queue all notifier, not single one like above, let see how:
but let support we have also OneSignalNotification:

```PHP
namespace App\Http\Controllers;

use App\Notifications\EmailNotification;
use App\Notifications\OneSignalNotification;
use Patienceman\Synca\Facades\Notifier;

class UsersController extends Controller {

    public function notifications(Notifier $notifier, User $user) {
        $application = Application::findById('1')->belongsToCompany()->user_id;
        $notification = [ 'message' => 'Application sent to job sent' ];
        
        $users = [
            'user' => $user
            'applicant' => $application
        ];

        $notifier->send([
            EmailNotification::process($notification)->to($users),
            OneSignalNotification::process($notification)->to($user),
        ])->onQueue();
    }

}
```

As u see above, we're working with payloads to notifier, Let see how to get all payload and all targeted user:

```PHP
namespace App\Notifications;

use Patienceman\Synca\NotifyHandler;

class EmailNotification extends NotifyHandler {
    /**
     * Execute notification actions
     * @return mixed
     */
    public function handle() {
        $this->message; // this will get single passed payload
        $this->payload(); // this will get all payload as object
        $this->recipients(); // this will get all targeted users
    }
}
```

There is tile also you want to send notification to all recipients without chose who: by just use function

```bash
$this->foreachUser() 
```

```PHP
$this->foreachUser(fn($user) => $this->sendToDatabase($user)); 
```

You held this function right!!?, This function can be used in Laravel DBNotification to store custom notification in table:

So let see full implementation:

```PHP
namespace App\Notifications;

use Patienceman\Synca\NotifyHandler;

class DatabaseNotification extends NotifyHandler {
    /**
     * Execute notification
     * @return mixed
     */
    public function handle() {
        $this->foreachUser(
            fn($user) => $this->sendToDatabase($user, $this)
        );
    }

    /**
     * Get the array to database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable) {
        return [
            'custom' => $this->message,
        ];
    }
}
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
Please make sure to update tests as appropriate.
