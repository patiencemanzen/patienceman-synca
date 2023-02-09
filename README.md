# Patienceman - Notifier

Provide a convenient way to interact with Notifications, including Emails, Dbnotification, and one signal notifications,
it just helps your declaration and interaction easier.

## Installation

Install the package doesn't require much requirement except to use the following command in the laravel terminal,  and you're good to go.

```bash
composer require patienceman/notifier
```

> Before get started let first create Queue table and Notifications:
> ```bash 
> php artisan queue:table
> php artisan notification:table
>  
> php artisan migrate
> ```
> 
> Finally, don't forget to instruct your application to use the database```driver``` by updating the ```QUEUE_CONNECTION``` variable in your application's ```env``` or any queue drive you use, consider [Laravel queue doc](https://laravel.com/docs/8.x/queues) file:
> ```php 
> QUEUE_CONNECTION=database 
> ```

## Usage

To start working with Noptifier,  u need to run command :tada: 
in your custom directories:

```bash
php artisan make:notifier EmailNotification
```
so it will create the filter file for u, Just in 
```php 
App\Notifiers
```

```PHP
namespace App\Notifications;

use Patienceman\Notifier\NotifyHandler;

class EmailNotification extends NotifyHandler {
    /**
     * Execute notification actions
     * @return mixed
     */
   public function handle() {
          // do whatever action inside handler
   }
}
```
:fire: :fire: What best move we make: in the world!!

So you may want even to specify the custom path for your Notifier, Just relax and add it in front of your notifier name.
Let's take again our current example.

```bash
php artisan make:notifier Model/EmailNotification
```
:wave: :wave: That is just what magic can make, awesome right!!?

To communicate/use your Notifier, you only need to call Notifier class, 
Let take a quick example in our CandidateController class to notify about application between creator and seeker

```PHP
namespace App\Http\Controllers;

use App\Notifications\EmailNotification;
use Patienceman\Notifier\Notifier;

class CandidateController extends Controller {

    public function application(Notifier $notifier) {
        $notifier->send([
            EmailNotification::process([ 'message' => 'Application sent to job sent' ]),
        ]);
    }

}
```
Boom Boom 🎉, from now on, we are able send our email notification anytime, any place.
So there is many feature comes with notifier, includes ```onQueue() ``` ```to($user1, $user2, ....) ```: let take a look;

```PHP
namespace App\Http\Controllers;

use App\Notifications\EmailNotification;
use Patienceman\Notifier\Notifier;

class CandidateController extends Controller {

    public function application(Notifier $notifier, User $user) {
        $jobCreator = Application::findById('1')->belongsToCompany()->user_id;

        $notifier->send([
            EmailNotification::process([ 'message' => 'Application sent to job sent' ])->to($user, $jobCreator)->onQueue(),
        ]);
    }

}
```
So to access the passed users you need to just call one by one using indexes: for **example**:
with ```php ->to($user, $user2); ```
```PHP
$this->user_1;
$this->user_2;
```

This is so cool, but there might be a time where you need to queue all notifier, not single one like above, let see how:
but let support we have also OneSignalNotification: 

```PHP
namespace App\Http\Controllers;

use App\Notifications\EmailNotification;
use App\Notifications\OneSignalNotification;
use Patienceman\Notifier\Notifier;

class CandidateController extends Controller {

    public function application(Notifier $notifier, User $user) {
        $jobCreator = Application::findById('1')->belongsToCompany()->user_id;

        $notifier->send([
            EmailNotification::process([ 'message' => 'Application sent to job sent' ])->to($user, $jobCreator),
            OneSignalNotification::process([ 'message' => 'Application sent to job sent' ])->to($user),
        ])->onQueue();
    }

}
```
As u see above, we're working with payloads to notifier, Let see how to get all payload and all targeted user:

```PHP
namespace App\Notifications;

use Patienceman\Notifier\NotifyHandler;
use Patienceman\Notifier\Traits\NotifyPayload;

class EmailNotification extends NotifyHandler {
    use NotifyPayload;

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
$this->foreachUser(fn($user) => $this->dbNotification($user, $this)); 
```

You held this function right!!?, This function can be used in Laravel DBNotification to store custom notification in table:

So let see full implementation:
```PHP
namespace App\Notifications;

use Patienceman\Notifier\NotifyHandler;
use Patienceman\Notifier\Traits\NotifyPayload;

class DatabaseNotification extends NotifyHandler {
    use NotifyPayload;

    /**
     * Execute notification
     * @return mixed
     */
    public function handle() {
        $this->foreachUser(fn($user) => $this->dbNotification($user, $this));
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

# Hi!👋🏼, Am Manirabona Patience🤴🏽
Software Developer (Php, Laravel, and Javascript), with 6+ years of experience, Am passionate about creating quality applications, and Never tired of learning, creating, and building,  I've been collaborating and contributing with different Teams and companies to develop their products from ideas up to Marketplace, including open source projects.
When am not working on technology, Patience loves watching documentaries, reading and writing books, traveling and exploring, history and psychology, Photograph, Painting, Playing Piano, and enjoys hanging out with devs But Mountains are better!

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
## Connect with Me
<p align="center">
	<a href="https://www.linkedin.com/in/manirabona-patience-3b08051b4"><img alt="Linkedin" title="Manirabona patience Linkedin" src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white"></a>
  <a href="https://github.com/manirabona-programer/manirabona-programer"><img alt="Github" title="Manirabona patience Github" src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white"></a>
  <a href="https://www.instagram.com/manirabona_walker"><img alt="Instagram" title="Manirabona Patience Instagram" src="https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white"></a>
	  <a href="https://twitter.com/ManirabonaW"><img alt="Twitter" title="Manirabona Patience Twitter" src="https://img.shields.io/badge/Twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white"></a>
	  </p>
