<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Paper;
use App\Models\PaperSection;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Seeding RUANG database...');

        // ── 1. Tags ─────────────────────────────────────────────────────────
        $this->command->info('📌 Creating tags...');
        $tagData = [
            ['name' => 'Laravel', 'color' => '#ef4444'],
            ['name' => 'JavaScript', 'color' => '#f59e0b'],
            ['name' => 'Python', 'color' => '#3b82f6'],
            ['name' => 'Database', 'color' => '#8b5cf6'],
            ['name' => 'Machine Learning', 'color' => '#10b981'],
            ['name' => 'UI/UX', 'color' => '#ec4899'],
            ['name' => 'Algoritma', 'color' => '#6366f1'],
            ['name' => 'Jaringan', 'color' => '#06b6d4'],
            ['name' => 'Mobile Dev', 'color' => '#84cc16'],
            ['name' => 'Keamanan', 'color' => '#f97316'],
        ];

        $tags = collect($tagData)->map(fn ($tag) => Tag::create([
            'name'  => $tag['name'],
            'slug'  => Str::slug($tag['name']),
            'color' => $tag['color'],
        ]));

        // ── 2. Admin User ────────────────────────────────────────────────────
        $this->command->info('👑 Creating admin user...');
        $admin = User::create([
            'name'              => 'Admin RUANG',
            'username'          => 'admin_ruang',
            'email'             => 'admin@ruang.dev',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'role'              => 'admin',
            'bio'               => 'Admin platform RUANG, siap bantu semua mahasiswa!',
        ]);

        // ── 3. Demo User ─────────────────────────────────────────────────────
        $this->command->info('🎓 Creating demo user...');
        $demoUser = User::create([
            'name'              => 'Rizky Pratama',
            'username'          => 'rizky_prtm',
            'email'             => 'rizky@ruang.dev',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'role'              => 'student',
            'bio'               => 'Mahasiswa Teknik Informatika, suka ngulik koding ✨',
        ]);

        // ── 4. Regular Users ─────────────────────────────────────────────────
        $this->command->info('👥 Creating 15 regular users...');
        $users = User::factory(15)->create();
        $allUsers = $users->push($admin)->push($demoUser);

        // ── 5. Subjects ──────────────────────────────────────────────────────
        $this->command->info('📚 Creating subjects...');
        $subjects = Subject::factory(8)->create([
            'created_by' => $admin->id,
        ]);

        // Attach users ke subjects
        foreach ($subjects as $subject) {
            $randomUsers = $allUsers->random(rand(3, 8));
            foreach ($randomUsers as $user) {
                if (!$subject->users()->where('user_id', $user->id)->exists()) {
                    $subject->users()->attach($user->id, ['role' => 'viewer']);
                }
            }
        }

        // Demo user masuk semua subject
        foreach ($subjects as $subject) {
            if (!$demoUser->subjects()->where('subject_id', $subject->id)->exists()) {
                $subject->users()->attach($demoUser->id, ['role' => 'viewer']);
            }
        }

        // ── 6. Papers ────────────────────────────────────────────────────────
        $this->command->info('📄 Creating papers with sections...');

        // Demo user papers
        $demoUserPapers = Paper::factory(5)->create([
            'user_id'    => $demoUser->id,
            'subject_id' => $subjects->random()->id,
        ]);

        // Published papers untuk explore
        $publicPapers = Paper::factory(10)->published()->create([
            'subject_id' => $subjects->random()->id,
        ]);

        $allPapers = $demoUserPapers->merge($publicPapers);

        // Buat sections untuk tiap paper
        foreach ($allPapers as $paper) {
            $sectionOrder = 0;
            $sections = [
                ['title' => 'Pendahuluan', 'type' => 'introduction'],
                ['title' => 'Tinjauan Pustaka', 'type' => 'body'],
                ['title' => 'Metodologi', 'type' => 'body'],
                ['title' => 'Hasil dan Pembahasan', 'type' => 'body'],
                ['title' => 'Kesimpulan', 'type' => 'conclusion'],
            ];

            foreach ($sections as $section) {
                PaperSection::create([
                    'paper_id' => $paper->id,
                    'title'    => $section['title'],
                    'type'     => $section['type'],
                    'content'  => '<p>' . implode('</p><p>', fake()->paragraphs(rand(1, 3))) . '</p>',
                    'order'    => $sectionOrder++,
                ]);
            }

            // Attach tags
            $paper->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        }

        // ── 7. Notes ─────────────────────────────────────────────────────────
        $this->command->info('✏️ Creating notes...');

        // Demo user notes
        Note::factory(8)->create([
            'user_id'    => $demoUser->id,
            'subject_id' => $subjects->random()->id,
        ]);

        // Pinned notes untuk demo user
        Note::factory(2)->pinned()->create([
            'user_id' => $demoUser->id,
        ]);

        // Random users notes
        foreach ($users->take(8) as $user) {
            Note::factory(rand(2, 5))->create([
                'user_id' => $user->id,
            ]);
        }

        $this->command->newLine();
        $this->command->info('✅ Database seeded successfully!');
        $this->command->table(
            ['Resource', 'Count'],
            [
                ['Users', $allUsers->count()],
                ['Subjects', $subjects->count()],
                ['Papers', $allPapers->count()],
                ['Tags', $tags->count()],
            ]
        );
        $this->command->newLine();
        $this->command->info('🔑 Login credentials:');
        $this->command->info('   Admin  → admin@ruang.dev / password');
        $this->command->info('   Demo   → rizky@ruang.dev / password');
    }
}
