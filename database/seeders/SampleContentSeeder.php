<?php

namespace Database\Seeders;

use App\Models\ContactSubmission;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@webcms.local')->first();
        if (!$admin) {
            return;
        }

        // Create sample posts
        $posts = [
            [
                'title' => ['en' => 'Getting Started with WebCMS'],
                'slug' => 'getting-started-with-webcms',
                'content' => ['en' => '<p>Welcome to WebCMS! This is your first post. You can edit or delete it from the admin panel.</p><p>WebCMS is a modern content management system built with Laravel, Filament, and Vue.js. It offers a clean, intuitive admin panel and a beautiful, responsive frontend.</p><h2>Features</h2><ul><li>Post and Page management</li><li>Multi-language support</li><li>Theme switching</li><li>Dark mode</li><li>Contact form with email notifications</li><li>Page builder with drag-and-drop blocks</li></ul>'],
                'excerpt' => ['en' => 'Welcome to WebCMS! Learn about the features and capabilities of your new content management system.'],
                'status' => 'published',
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => ['en' => 'How to Create and Manage Content'],
                'slug' => 'how-to-create-manage-content',
                'content' => ['en' => '<p>Creating content in WebCMS is straightforward. Navigate to the admin panel and use the Posts or Pages sections to create new content.</p><h2>Creating Posts</h2><p>Posts are great for blog entries, news articles, and updates. Each post supports a rich text editor, featured images, and SEO metadata.</p><h2>Creating Pages</h2><p>Pages are ideal for static content like About, Services, and FAQ. Pages also support a visual page builder with blocks.</p>'],
                'excerpt' => ['en' => 'Learn how to create and manage posts and pages in your WebCMS admin panel.'],
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => ['en' => 'Customizing Your Theme'],
                'slug' => 'customizing-your-theme',
                'content' => ['en' => '<p>WebCMS supports multiple themes that you can switch between from the admin panel. Each theme controls the visual appearance of your frontend.</p><p>Navigate to Appearance > Themes to see available themes and activate the one you prefer.</p>'],
                'excerpt' => ['en' => 'Discover how to switch themes and customize the look of your website.'],
                'status' => 'published',
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => ['en' => 'Multi-Language Support'],
                'slug' => 'multi-language-support',
                'content' => ['en' => '<p>WebCMS supports multiple languages out of the box. You can translate your content, menu items, and frontend strings into any language.</p><p>Use the Translation Editor in Settings to manage your translation strings, and the Language Settings to configure which languages are available.</p>'],
                'excerpt' => ['en' => 'Learn about the multi-language capabilities of WebCMS.'],
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => ['en' => 'Using the Page Builder'],
                'slug' => 'using-the-page-builder',
                'content' => ['en' => '<p>The page builder allows you to create rich, visual layouts using drag-and-drop blocks. Available blocks include Hero sections, Text content, Images, Galleries, Call-to-Action banners, and Contact forms.</p><p>Each block is fully responsive and will look great on all screen sizes.</p>'],
                'excerpt' => ['en' => 'Create stunning pages with the built-in visual page builder.'],
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => ['en' => 'Dark Mode and Accessibility'],
                'slug' => 'dark-mode-accessibility',
                'content' => ['en' => '<p>WebCMS includes built-in dark mode support for both the admin panel and the public frontend. Users can toggle between light and dark modes using the toggle in the header.</p><p>The preference is saved in the browser and persists across sessions.</p>'],
                'excerpt' => ['en' => 'WebCMS includes dark mode support across the entire application.'],
                'status' => 'published',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => ['en' => 'Draft Post: Coming Soon'],
                'slug' => 'draft-coming-soon',
                'content' => ['en' => '<p>This is a draft post that is not visible on the frontend.</p>'],
                'excerpt' => ['en' => 'A draft post example.'],
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($posts as $postData) {
            Post::firstOrCreate(
                ['slug' => $postData['slug']],
                array_merge($postData, ['author_id' => $admin->id])
            );
        }

        // Create homepage
        $homePage = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => ['en' => 'Home'],
                'content' => ['en' => ''],
                'status' => 'published',
                'template' => 'default',
                'layout_data' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'title' => 'Welcome to WebCMS',
                            'subtitle' => 'A modern, flexible content management system',
                            'background_image' => null,
                            'cta_text' => 'Get Started',
                            'cta_url' => '/posts',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'data' => [
                            'content' => '<h2>Build Beautiful Websites</h2><p>WebCMS is a modern content management system built with Laravel, Filament, and Vue.js. It offers a clean admin panel and a beautiful frontend with theme support, dark mode, and multi-language capabilities.</p>',
                        ],
                    ],
                    [
                        'type' => 'latest_posts',
                        'data' => [
                            'title' => 'Latest Posts',
                            'count' => 6,
                            'columns' => '3',
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'data' => [
                            'title' => 'Ready to get started?',
                            'description' => 'Explore our blog or get in touch with us.',
                            'button_text' => 'Contact Us',
                            'button_url' => '/contact',
                        ],
                    ],
                ],
                'sort_order' => 0,
            ]
        );

        // Set homepage in settings
        $homepageSettings = app(\App\Settings\HomepageSettings::class);
        if (!$homepageSettings->homepage_page_id) {
            $homepageSettings->homepage_page_id = $homePage->id;
            $homepageSettings->save();
        }

        // Create sample pages
        Page::firstOrCreate(
            ['slug' => 'about'],
            [
                'title' => ['en' => 'About Us'],
                'content' => ['en' => '<p>We are a team passionate about building great web experiences. WebCMS is our flagship content management system designed for simplicity and power.</p>'],
                'status' => 'published',
                'template' => 'default',
                'layout_data' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'title' => 'About Us',
                            'subtitle' => 'Building the web, one page at a time',
                            'background_image' => null,
                            'cta_text' => 'Contact Us',
                            'cta_url' => '/contact',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'data' => [
                            'content' => '<h2>Our Mission</h2><p>We believe that managing web content should be simple, intuitive, and enjoyable. WebCMS is built with modern technologies to give you the best experience.</p><h2>Our Stack</h2><p>Laravel, Filament, Vue.js, Inertia.js, and Tailwind CSS power every aspect of WebCMS, from the admin panel to the public frontend.</p>',
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'data' => [
                            'title' => 'Ready to get started?',
                            'description' => 'Explore our blog or get in touch with us.',
                            'button_text' => 'View Blog',
                            'button_url' => '/posts',
                        ],
                    ],
                ],
                'sort_order' => 1,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'services'],
            [
                'title' => ['en' => 'Our Services'],
                'content' => ['en' => '<p>We offer a range of web development and content management services.</p>'],
                'status' => 'published',
                'template' => 'default',
                'layout_data' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'title' => 'Our Services',
                            'subtitle' => 'What we can do for you',
                            'background_image' => null,
                            'cta_text' => null,
                            'cta_url' => null,
                        ],
                    ],
                    [
                        'type' => 'text',
                        'data' => [
                            'content' => '<h2>Web Development</h2><p>Custom web applications built with Laravel and modern frontend frameworks.</p><h2>CMS Solutions</h2><p>Content management systems tailored to your needs.</p><h2>Consulting</h2><p>Technical consulting and architecture design for your web projects.</p>',
                        ],
                    ],
                ],
                'sort_order' => 2,
            ]
        );

        // Add About and Services to header menu
        $headerMenu = \App\Models\Menu::where('location', 'header')->first();
        if ($headerMenu) {
            MenuItem::firstOrCreate(
                ['menu_id' => $headerMenu->id, 'url' => '/about'],
                ['title' => ['en' => 'About'], 'type' => 'custom', 'order' => 3]
            );
            MenuItem::firstOrCreate(
                ['menu_id' => $headerMenu->id, 'url' => '/services'],
                ['title' => ['en' => 'Services'], 'type' => 'custom', 'order' => 4]
            );
        }

        // Create sample contact submissions
        $contacts = [
            ['name' => 'John Smith', 'email' => 'john@example.com', 'subject' => 'General Inquiry', 'message' => 'I would like to know more about your services. Could you send me more information?', 'is_read' => true],
            ['name' => 'Sarah Johnson', 'email' => 'sarah@example.com', 'subject' => 'Partnership Opportunity', 'message' => 'We are interested in a potential partnership. Let us know if you are available for a meeting.', 'is_read' => true],
            ['name' => 'Mike Davis', 'email' => 'mike@example.com', 'subject' => 'Technical Support', 'message' => 'I need help with setting up the CMS on my server. Can you assist?', 'is_read' => false],
            ['name' => 'Emily Brown', 'email' => 'emily@example.com', 'subject' => 'Feedback', 'message' => 'Great CMS! I love the dark mode feature and the page builder. Keep up the good work!', 'is_read' => false],
            ['name' => 'Alex Wilson', 'email' => 'alex@example.com', 'subject' => null, 'message' => 'Quick question: do you support multi-site installations?', 'is_read' => false],
        ];

        foreach ($contacts as $contact) {
            ContactSubmission::firstOrCreate(
                ['email' => $contact['email'], 'message' => $contact['message']],
                $contact
            );
        }
    }
}
