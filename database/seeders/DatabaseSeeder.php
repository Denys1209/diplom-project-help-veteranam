<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VeteranProfile;
use App\Models\HelpCategory;
use App\Models\HelpRequest;
use App\Models\RequestComment;
use App\Models\RequestPhoto;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '+380501234567',
            'address' => 'вул. Собранецька 20, Ужгород',
            'role' => 'admin',
            'email_verified_at' => now(),
            'approval_status' => "approved",
        ]);

        // Create volunteer users
        $this->seedVolunteers();

        // Create veteran users and their profiles
        $this->seedVeteransAndProfiles();

        // Seed help categories
        $this->seedHelpCategories();

        // Seed help requests
        $this->seedHelpRequests();

        // Seed comments
        $this->seedComments();

        // Seed photos
        $this->seedPhotos();
    }

    /**
     * Seed the help categories.
     */
    private function seedHelpCategories(): void
    {
        $categories = [
            [
                'name' => 'Транспортування',
            ],
            [
                'name' => 'Ремонт житла',
            ],
            [
                'name' => 'Медичні потреби',
            ],
            [
                'name' => 'Доставка продуктів',
            ],
            [
                'name' => 'Юридична допомога',
            ],
            [
                'name' => 'Психологічна підтримка',
            ],
            [
                'name' => 'Фінансова допомога',
            ],
            [
                'name' => 'Побутова допомога',
            ],
            [
                'name' => 'Інше',
            ]
        ];

        foreach ($categories as $category) {
            HelpCategory::create($category);
        }
    }

    /**
     * Seed volunteer users.
     */
    private function seedVolunteers(): void
    {
        $volunteers = [
            [
                'name' => 'Іван Петренко',
                'email' => 'ivan.petrenko@example.com',
                'password' => Hash::make('password'),
                'phone' => '+380501234568',
                'address' => 'вул. Собранецька 42, Ужгород',
                'role' => 'volunteer',
                'approval_status' => "approved",
            ],
            [
                'name' => 'Марія Ковальчук',
                'email' => 'maria.kovalchuk@example.com',
                'password' => Hash::make('password'),
                'phone' => '+380501234569',
                'address' => 'вул. Швабська 15, Ужгород',
                'role' => 'volunteer',
                'email_verified_at' => now(),
                'approval_status' => "approved",
            ],
            [
                'name' => 'Олег Шевченко',
                'email' => 'oleh.shevchenko@example.com',
                'password' => Hash::make('password'),
                'phone' => '+380501234570',
                'address' => 'вул. Минайська 30, Ужгород',
                'role' => 'volunteer',
                'approval_status' => "approved",
            ],
            [
                'name' => 'Наталія Бондаренко',
                'email' => 'natalia.bondarenko@example.com',
                'password' => Hash::make('password'),
                'phone' => '+380501234571',
                'address' => 'вул. Капушанська 12, Ужгород',
                'role' => 'volunteer',
                'approval_status' => "approved",
            ],
            [
                'name' => 'Андрій Мельник',
                'email' => 'andriy.melnyk@example.com',
                'password' => Hash::make('password'),
                'phone' => '+380501234572',
                'address' => 'пл. Петефі 5, Ужгород',
                'role' => 'volunteer',
                'approval_status' => "approved",
            ],
        ];

        foreach ($volunteers as $volunteer) {
            User::create($volunteer);
        }
    }

    /**
     * Seed veteran users and their profiles.
     */
    private function seedVeteransAndProfiles(): void
    {
        $veterans = [
            [
                'user' => [
                    'name' => 'Василь Гончаренко',
                    'email' => 'vasyl.goncharenko@example.com',
                    'password' => Hash::make('password'),
                    'phone' => '+380501234573',
                    'address' => 'вул. Грушевського 25, Ужгород',
                    'approval_status' => "approved",
                    'email_verified_at' => now(),
                ],
                'profile' => [
                    'needs_description' => 'Потребує допомоги з реабілітацією після поранення',
                    'military_unit' => '95 окрема десантно-штурмова бригада',
                    'service_period' => '2022-2024',
                    'medical_conditions' => 'Травма ноги, ПТСР',
                    'is_visible' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Петро Коваленко',
                    'email' => 'petro.kovalenko@example.com',
                    'password' => Hash::make('password'),
                    'phone' => '+380501234574',
                    'address' => 'вул. Заньковецької 38, Ужгород',
                    'role' => 'veteran',
                'approval_status' => "approved",
                ],
                'profile' => [
                    'needs_description' => 'Потребує допомоги з ремонтом житла',
                    'military_unit' => '24 окрема механізована бригада',
                    'service_period' => '2014-2023',
                    'medical_conditions' => 'Контузія, часткова втрата слуху',
                    'is_visible' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Олена Савченко',
                    'email' => 'olena.savchenko@example.com',
                    'password' => Hash::make('password'),
                    'phone' => '+380501234575',
                    'address' => 'вул. Можайського 5, Ужгород',
                    'role' => 'veteran',
                'approval_status' => "approved",
                ],
                'profile' => [
                    'needs_description' => 'Потребує юридичної допомоги щодо пільг',
                    'military_unit' => '128 окрема гірсько-штурмова бригада',
                    'service_period' => '2016-2022',
                    'medical_conditions' => 'Здорова',
                    'is_visible' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Михайло Дмитренко',
                    'email' => 'mykhailo.dmytrenko@example.com',
                    'password' => Hash::make('password'),
                    'phone' => '+380501234576',
                    'address' => 'вул. Університетська 12, Ужгород',
                    'role' => 'veteran',
                'approval_status' => "approved",
                ],
                'profile' => [
                    'needs_description' => 'Потребує психологічної підтримки та допомоги з працевлаштуванням',
                    'military_unit' => '36 окрема бригада морської піхоти',
                    'service_period' => '2018-2023',
                    'medical_conditions' => 'ПТСР, депресія',
                    'is_visible' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Ірина Литвин',
                    'email' => 'iryna.lytvyn@example.com',
                    'password' => Hash::make('password'),
                    'phone' => '+380501234577',
                    'address' => 'вул. Легоцького 8, Ужгород',
                    'role' => 'veteran',
                'approval_status' => "approved",
                ],
                'profile' => [
                    'needs_description' => 'Потребує медичної допомоги та реабілітації',
                    'military_unit' => '10 окрема гірсько-штурмова бригада',
                    'service_period' => '2015-2022',
                    'medical_conditions' => 'Ампутація кисті руки, проблеми з хребтом',
                    'is_visible' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Сергій Павленко',
                    'email' => 'serhiy.pavlenko@example.com',
                    'password' => Hash::make('password'),
                    'phone' => '+380501234578',
                    'address' => 'вул. Перемоги 45, Ужгород',
                    'role' => 'veteran',
                'approval_status' => "approved",
                ],
                'profile' => [
                    'needs_description' => 'Потребує допомоги з побутовими питаннями',
                    'military_unit' => '72 окрема механізована бригада',
                    'service_period' => '2019-2023',
                    'medical_conditions' => 'Травма спини',
                    'is_visible' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Богдан Ткаченко',
                    'email' => 'bohdan.tkachenko@example.com',
                    'password' => Hash::make('password'),
                    'phone' => '+380501234579',
                    'address' => 'вул. Загорська 20, Ужгород',
                    'role' => 'veteran',
                'approval_status' => "approved",
                ],
                'profile' => [
                    'needs_description' => 'Потребує транспортування на медичні процедури',
                    'military_unit' => '93 окрема механізована бригада',
                    'service_period' => '2014-2021',
                    'medical_conditions' => 'Обмежена рухливість, проблеми із зором',
                    'is_visible' => true,
                ]
            ],
        ];

        foreach ($veterans as $veteranData) {
            $user = User::create($veteranData['user']);
            $veteranData['profile']['user_id'] = $user->id;
            VeteranProfile::create($veteranData['profile']);
        }
    }

    /**
     * Seed help requests.
     */
    private function seedHelpRequests(): void
    {
        // Base coordinates for Uzhhorod
        $baseLatitude = 48.6208;
        $baseLongitude = 22.2879;

        $statuses = ['pending', 'in_progress', 'completed'];
        $urgencies = ['low', 'medium', 'high', 'critical'];

        // Get all veterans, volunteers and categories
        $veterans = User::where('role', 'veteran')->get();
        $volunteers = User::where('role', 'volunteer')->get();
        $categories = HelpCategory::all();

        $requests = [
            [
                'title' => 'Потрібна допомога з транспортуванням до лікарні',
                'description' => 'Потрібно відвезти мене на медичний огляд у госпіталь. Маю проблеми з пересуванням після травми, тому потребую допомоги.',
                'status' => 'pending',
                'urgency' => 'medium',
                'category_id' => 1, // Транспортування
                'deadline' => now()->addDays(5),
            ],
            [
                'title' => 'Необхідний ремонт даху',
                'description' => 'Після обстрілу пошкоджено дах будинку. Потрібна допомога з матеріалами та робочою силою для проведення ремонту перед сезоном дощів.',
                'status' => 'in_progress',
                'urgency' => 'high',
                'category_id' => 2, // Ремонт житла
                'deadline' => now()->addDays(14),
            ],
            [
                'title' => 'Потрібні медикаменти',
                'description' => 'Необхідні ліки для лікування після поранення. Список прикріплено. Також потрібна консультація щодо їх застосування.',
                'status' => 'completed',
                'urgency' => 'high',
                'category_id' => 3, // Медичні потреби
                'deadline' => now()->subDays(3),
                'completed_at' => now()->subDays(1),
            ],
            [
                'title' => 'Допомога з продуктами',
                'description' => 'Через обмежену мобільність не можу самостійно відвідувати магазини. Потребую допомоги з доставкою основних продуктів харчування раз на тиждень.',
                'status' => 'in_progress',
                'urgency' => 'medium',
                'category_id' => 4, // Доставка продуктів
                'deadline' => now()->addDays(2),
            ],
            [
                'title' => 'Консультація з юристом щодо пільг',
                'description' => 'Потрібна консультація щодо оформлення пільг та соціальних виплат для ветеранів. Не розумію процедуру та які документи потрібні.',
                'status' => 'pending',
                'urgency' => 'low',
                'category_id' => 5, // Юридична допомога
                'deadline' => now()->addDays(10),
            ],
            [
                'title' => 'Психологічна підтримка',
                'description' => 'Після повернення з фронту маю проблеми зі сном та адаптацією до мирного життя. Потребую консультацій психолога.',
                'status' => 'in_progress',
                'urgency' => 'medium',
                'category_id' => 6, // Психологічна підтримка
                'deadline' => now()->addDays(7),
            ],
            [
                'title' => 'Допомога з оплатою рахунків за комунальні послуги',
                'description' => 'Через тривале лікування накопичилися борги за комунальні послуги. Потребую фінансової допомоги для їх погашення.',
                'status' => 'completed',
                'urgency' => 'high',
                'category_id' => 7, // Фінансова допомога
                'deadline' => now()->subDays(5),
                'completed_at' => now()->subDays(2),
            ],
            [
                'title' => 'Потрібна допомога з ремонтом сантехніки',
                'description' => 'Протікає кран та унітаз. Через травму не можу самостійно виконати ремонт. Потрібна допомога майстра.',
                'status' => 'pending',
                'urgency' => 'medium',
                'category_id' => 8, // Побутова допомога
                'deadline' => now()->addDays(4),
            ],
            [
                'title' => 'Допомога з переїздом',
                'description' => 'Потребую допомоги з перевезенням речей до нового житла. Маю інвалідність і не можу самостійно перенести меблі та важкі речі.',
                'status' => 'pending',
                'urgency' => 'low',
                'category_id' => 9, // Інше
                'deadline' => now()->addDays(12),
            ],
            [
                'title' => 'Потрібен супровід до військкомату',
                'description' => 'Потрібно відвідати військкомат для оформлення документів. Маю проблеми з орієнтацією в місті та потребую супроводу.',
                'status' => 'in_progress',
                'urgency' => 'medium',
                'category_id' => 1, // Транспортування
                'deadline' => now()->addDays(3),
            ],
            [
                'title' => 'Заміна вікон у квартирі',
                'description' => 'Після обстрілів пошкоджені вікна в квартирі. Зима близько, тому потрібна допомога з їх заміною.',
                'status' => 'pending',
                'urgency' => 'high',
                'category_id' => 2, // Ремонт житла
                'deadline' => now()->addDays(20),
            ],
            [
                'title' => 'Потрібен масаж та реабілітація',
                'description' => 'Після поранення потребую курсу масажу та фізичної реабілітації. Самостійно не можу оплатити ці послуги.',
                'status' => 'pending',
                'urgency' => 'medium',
                'category_id' => 3, // Медичні потреби
                'deadline' => now()->addDays(7),
            ],
            [
                'title' => 'Допомога з придбанням товарів для дітей',
                'description' => 'Маю трьох дітей і потребую допомоги з придбанням шкільного приладдя та одягу. Після повернення з фронту маю фінансові труднощі.',
                'status' => 'in_progress',
                'urgency' => 'medium',
                'category_id' => 4, // Доставка продуктів
                'deadline' => now()->addDays(8),
            ],
            [
                'title' => 'Допомога з оформленням пенсії по інвалідності',
                'description' => 'Потрібна юридична допомога з оформленням документів для отримання пенсії по інвалідності внаслідок поранення.',
                'status' => 'completed',
                'urgency' => 'high',
                'category_id' => 5, // Юридична допомога
                'deadline' => now()->subDays(10),
                'completed_at' => now()->subDays(3),
            ],
             [
        'title' => 'Потрібна допомога з доглядом за дитиною',
        'description' => 'Після поранення маю проблеми з пересуванням та доглядом за дитиною. Потрібна допомога з доглядом та супроводом дитини до школи.',
        'status' => 'pending',
        'urgency' => 'medium',
        'category_id' => 4, // Доставка продуктів
        'deadline' => now()->addDays(6),
    ],
    [
        'title' => 'Ремонт протікання опалення',
        'description' => 'Через пошкодження під час обстрілу вийшло з ладу опалення в будинку. Потрібна термінова допомога для підготовки до зими.',
        'status' => 'pending',
        'urgency' => 'high',
        'category_id' => 2, // Ремонт житла
        'deadline' => now()->addDays(15),
    ],
    [
        'title' => 'Психологічна підтримка сім\'ї',
        'description' => 'Моя родина потребує професійної психологічної допомоги після пережитого стресу. Особливо важливо підтримати дітей.',
        'status' => 'pending',
        'urgency' => 'medium',
        'category_id' => 6, // Психологічна підтримка
        'deadline' => now()->addDays(9),
    ],
    [
        'title' => 'Допомога з відновленням документів',
        'description' => 'Під час евакуації загубив документи. Потрібна допомога з їх відновленням та оформленням нових.',
        'status' => 'in_progress',
        'urgency' => 'low',
        'category_id' => 5, // Юридична допомога
        'deadline' => now()->addDays(18),
    ],
    [
        'title' => 'Потрібна допомога з адаптацією до протезу',
        'description' => 'Отримав протез після поранення. Потрібна професійна допомога з його налаштуванням та навчанням ходьбі.',
        'status' => 'pending',
        'urgency' => 'high',
        'category_id' => 3, // Медичні потреби
        'deadline' => now()->addDays(11),
    ],
    [
        'title' => 'Ремонт електрики в будинку',
        'description' => 'Через пошкодження під час обстрілу вийшла з ладу електрика в будинку. Потрібна термінова допомога для забезпечення безпеки.',
        'status' => 'pending',
        'urgency' => 'high',
        'category_id' => 2, // Ремонт житла
        'deadline' => now()->addDays(13),
    ],
    [
        'title' => 'Потрібна допомога з орієнтацією в місті',
        'description' => 'Після поранення маю проблеми зі сприйняттям просторових відносин. Потрібна допомога з навигацією по місту.',
        'status' => 'in_progress',
        'urgency' => 'medium',
        'category_id' => 1, // Транспортування
        'deadline' => now()->addDays(5),
    ],
    [
        'title' => 'Допомога з відновленням бізнесу',
        'description' => 'Мій малий бізнес постраждав через воєнні дії. Потрібна допомога з його відновленням та фінансуванням.',
        'status' => 'pending',
        'urgency' => 'low',
        'category_id' => 7, // Фінансова допомога
        'deadline' => now()->addDays(25),
    ],
    [
        'title' => 'Потрібен курс реабілітації для дітей',
        'description' => 'Діти потребують спеціальної реабілітаційної програми після пережитого стресу та травми.',
        'status' => 'pending',
        'urgency' => 'medium',
        'category_id' => 3, // Медичні потреби
        'deadline' => now()->addDays(16),
    ],
        ];

        // Create help requests
        foreach ($requests as $index => $requestData) {
            // Select random veteran
            $veteran = $veterans->random();

            // Add slight variation to coordinates for each request
            $latVariation = (rand(-100, 100) / 1000);
            $lngVariation = (rand(-100, 100) / 1000);

            $requestData['veteran_id'] = $veteran->id;
            $requestData['latitude'] = $baseLatitude + $latVariation;
            $requestData['longitude'] = $baseLongitude + $lngVariation;

            // Assign volunteer for in_progress and completed requests
            if ($requestData['status'] != 'pending') {
                $requestData['volunteer_id'] = $volunteers->random()->id;
            }

            HelpRequest::create($requestData);
        }
    }

    /**
     * Seed comments.
     */
    private function seedComments(): void
    {
        $users = User::all();
        $helpRequests = HelpRequest::all();

        $comments = [
            'Можу допомогти вам з цим питанням. Коли вам буде зручно зустрітися?',
            'Я щойно побачив ваш запит. Зв\'яжуся з вами телефоном для узгодження деталей.',
            'Мені потрібні додаткові деталі для того, щоб краще зрозуміти ваші потреби.',
            'Дякую за детальний опис проблеми. Я вже придбав необхідні матеріали і готовий розпочати роботу.',
            'Роботу виконано. Все гаразд?',
            'Потрібна додаткова інформація щодо розкладу роботи закладу, який вам потрібно відвідати.',
            'Я буду в вашому районі завтра. Можу заїхати допомогти з цим питанням.',
            'Чи є у вас алергія на якісь продукти? Хочу уточнити перед закупівлею.',
            'Завдання виконано успішно. Радий, що зміг допомогти!',
            'Підібрав для вас кілька варіантів вирішення проблеми. Давайте обговоримо, який підійде найкраще.',
            'На жаль, не зможу допомогти в зазначений час. Можливо, є варіант перенести на інший день?',
            'Виникли деякі питання під час виконання завдання. Можна вам зателефонувати для уточнення?',
            'Чудово! Робота виконана, все працює як слід?',
            'Потрібна порада фахівця. Я організую консультацію з експертом у цій галузі.',
            'Дякую за довіру. Зроблю все можливе, щоб допомогти вам з цією проблемою.',
        ];

        // Add 30 comments to random requests
        for ($i = 0; $i < 30; $i++) {
            $helpRequest = $helpRequests->random();
            $user = $users->random();

            RequestComment::create([
                'help_request_id' => $helpRequest->id,
                'user_id' => $user->id,
                'comment' => $comments[array_rand($comments)],
            ]);
        }
    }

    /**
     * Seed photos.
     */
    private function seedPhotos(): void
    {
        $users = User::all();
        $helpRequests = HelpRequest::all();

        $photoPaths = [
            'seedPhotos/request1_before.jpg',
            'seedPhotos/request1_after.jpg',
            'seedPhotos/request2_damage.jpg',
            'seedPhotos/request2_repair.jpg',
            'seedPhotos/request3_medicines.jpg',
            'seedPhotos/request4_groceries.jpg',
            'seedPhotos/request7_documents.jpg',
            'seedPhotos/request8_plumbing.jpg',
            'seedPhotos/request8_fixed.jpg',
            'seedPhotos/request11_windows.jpg',
        ];

        $captions = [
            'Фото пошкодження',
            'Фото після ремонту',
            'Необхідні медикаменти',
            'Доставлені продукти',
            'Документи для оформлення',
            'Стан сантехніки до ремонту',
            'Відремонтована сантехніка',
            'Пошкоджені вікна',
            'Загальний вигляд об\'єкту',
            'Результат виконаної роботи',
        ];

        // Add photos to random requests
        for ($i = 0; $i < 15; $i++) {
            $helpRequest = $helpRequests->random();
            $user = $users->random();
            $isCompletionPhoto = rand(0, 5) == 0; // 1 in 6 chance to be a completion photo

            RequestPhoto::create([
                'help_request_id' => $helpRequest->id,
                'user_id' => $user->id,
                'photo_path' => $photoPaths[array_rand($photoPaths)],
                'caption' => $captions[array_rand($captions)],
                'is_completion_photo' => $isCompletionPhoto,
            ]);
        }
    }
}
