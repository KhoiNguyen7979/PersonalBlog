-- Seed data for blog_riel
-- Inserts 5 new users, blog posts for all users, and reviews
-- Run AFTER database_blog_riel.sql

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

USE `blog_riel`;

-- =====================================================
-- 1. Insert 5 new user accounts
-- =====================================================
INSERT INTO `NguoiDung` (`Email`, `HoTenNguoiDung`, `TenDangNhap`, `MatKhau`, `MoTa`, `VaiTro`) VALUES
('sarah@gmail.com', 'Sarah Johnson', 'sarahj', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Travel enthusiast and tech writer. I love exploring new places and sharing my experiences through blog posts.', 'user'),
('mike@gmail.com', 'Mike Chen', 'mikechen', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Software developer by day, musician by night. Passionate about coding, guitars, and everything in between.', 'user'),
('emma@gmail.com', 'Emma Wilson', 'emmaw', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Digital nomad and lifestyle blogger. Currently based in Da Nang, Vietnam. Coffee addict.', 'user'),
('alex@gmail.com', 'Alex Rivera', 'alexr', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Full-stack developer and open source contributor. I write about web development and best practices.', 'user'),
('lisa@gmail.com', 'Lisa Park', 'lisap', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Music producer and sound engineer. Sharing my journey in the music industry and production tips.', 'user');

-- =====================================================
-- 2. Blog posts - Sarah Johnson
-- =====================================================
INSERT INTO `BaiViet` (`TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ThoiGianDoc`, `LuotXem`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
(
  'My Journey Through Southeast Asia',
  'My three-month backpacking adventure across Southeast Asia was one of the most transformative experiences of my life. I started in Bangkok, Thailand, where the energetic street food and vibrant temples immediately captured my heart. From the chaotic Chatuchak market to the serene temples along the Chao Phraya river, every corner offered a new discovery. I made sure to visit the Grand Palace and the stunning water pagodas of Koh Yao Yai island.\n\nFrom Thailand, I traveled to Vietnam via a slow boat through the Mekong Delta. The landscape changed from flat farmlands to the bustling streets of Ho Chi Minh City. I spent two weeks traveling north, stopping in Hoi An for its lantern-lit streets and tailor shops, then Hanoi for its centuries-old architecture and incredible street food. The Ha Long Bay cruise was a surreal experience with its emerald waters and towering limestone islands.\n\nMy final destination was Cambodia. Angkor Wat at sunrise was everything people said it would be and more. I rented a bicycle and spent an entire day exploring the temple complex, getting lost among ancient ruins and jungle-covered structures. The Cambodian people were incredibly warm despite their country\'s tragic history. This trip taught me to travel light, embrace spontaneity, and connect with people from all walks of life. I already have my next Southeast Asia itinerary planned.',
  'A 3-month backpacking adventure across Thailand, Vietnam, and Cambodia.',
  '2026-07-10 08:00:00', 5, 45, 'sarah@gmail.com', 'story'
),
(
  'Top 10 Tech Gadgets for Travelers',
  'As a travel lover and a tech enthusiast, I have compiled a list of gadgets that make traveling much easier and more connected. First on the list is a reliable portable charger. It is essential for keeping your phone, laptop, and e-reader powered up during long flights and bus rides. I look for models with multiple ports so you can charge multiple devices at once.\n\nNext up is a good pair of noise-canceling headphones. Whether you are on a noisy bus or in a crowded co-working space, noise-canceling headphones are a game changer. I also recommend a portable Bluetooth speaker for music on the go. The JBL Clip 3 is my personal favorite - it is compact, waterproof, and has surprising sound quality.\n\nOther essentials include a portable Wi-Fi router for sharing internet access across devices, a Kindle for easier reading on flights, and a travel organizer to keep chargers, passports, and earplugs neatly organized. Do not forget a lightweight laptop like the MacBook Air, which is perfect for editing photos and writing on the go.',
  'Essential gadgets that make traveling easier and more connected.',
  '2026-07-11 10:00:00', 3, 38, 'sarah@gmail.com', 'technology'
),
(
  'How to Start a Travel Blog',
  'Starting a travel blog is an exciting way to document your adventures and connect with a global audience. The first step is to choose a blogging platform. WordPress is the most popular choice due to its flexibility and extensive plugin ecosystem. However, you can also use Hugo or other static site generators for more control.\n\nNext, pick a niche that reflects your personality and travel style. Your domain name should be memorable and easy to spell. A logo and a clean theme will help your site stand out. Invest in a good hosting provider like SiteGround or Bluehost for reliability and speed.\n\nFinally, focus on creating high-quality content. Take great photos, write engaging stories, and post consistently. Use SEO best practices to help your articles rank on Google. Social media is your best friend - share your content on Instagram, Pinterest, and Facebook to drive traffic to your blog.',
  'Step-by-step guide to creating your own travel blog from scratch.',
  '2026-07-12 09:00:00', 4, 29, 'sarah@gmail.com', 'skill'
),
(
  'The Sound of Bangkok',
  'Bangkok\'s music scene is as vibrant and diverse as the city itself. From the live music venues on Khao San Road to the intimate bar scenes in Sukhumvit, there is always something musical happening at any hour. I had the pleasure of exploring the local jazz formation venues, where live bands play a blend of traditional Thai music with modern jazz.\n\nOne of my favorite experiences was visiting a live music festival on Khao San Road. The energy that night was electrifying. Local bands played everything from indie-rock to indie-folk, and the crowd was incredibly engaged. The fusion of English and Thai musical elements created an unforgettable atmosphere.\n\nBangkok is also a hub for near-pop artists and independent musicians experimenting with unique sounds. The city\'s street art scene extends to its music venues, with street musicians performing outside shops and squatters. If you are a music lover, Bangkok should be at the top of your travel list.',
  'Discovering the vibrant music scene in Thailand\'s capital city.',
  '2026-07-13 14:00:00', 4, 52, 'sarah@gmail.com', 'music'
);

-- =====================================================
-- 3. Blog posts - Mike Chen
-- =====================================================
INSERT INTO `BaiViet` (`TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ThoiGianDoc`, `LuotXem`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
(
  'Building Real-Time Apps with WebSockets',
  'WebSockets have revolutionized how we build real-time applications. Unlike traditional HTTP requests, which are single-directional, WebSockets allow for bidirectional communication between a client and a server. This enables features like live chat, real-time notifications, and collaborative editing that users have come to expect.\n\nWhen building a WebSocket application, the choice of backend technology matters. Node.js with WS is a solid choice for handling large numbers of concurrent connections. On the frontend, the WebSocket API provides a simple interface for creating and managing connections. For more complex applications, libraries like Socket.IO can abstract away much of the complexity.\n\nSome practical considerations include managing state, handling reconnections, and scaling across multiple nodes. WebSocket servers can be clustered to distribute load, and event sources like RabbitMQ and PubSub help scale messaging across multiple servers. Starting with a simple chat app is a great way to learn the fundamentals of WebSockets.',
  'A deep dive into WebSocket technology and its applications.',
  '2026-07-10 09:00:00', 4, 62, 'mike@gmail.com', 'technology'
),
(
  'Learning Guitar as an Adult',
  'Picking up a guitar at the age of 30 was one of the best decisions I ever made. Many people think it is too late to learn a musical instrument when you are an adult, but that could not be further from the truth. One of the advantages of learning as an adult is the discipline and focus you bring to the practice.\n\nI started by buying a decent acoustic guitar and using online resources like YouTube tutorials and Udemy. The key was to be patient with myself - I did not expect to play like Hendrix overnight. Starting with open chords and barre chords helped me build finger memory and muscle memory. Setting a daily 15-minute practice routine was crucial.\n\nNow, after two years, I can play my favorite songs and even perform at local open mic nights. The therapeutic benefit of playing music is undeniable - it is a great stress reliever and a creative outlet. My advice to anyone in their 30s or 40s is simply: go for it. The best time to learn was yesterday, but the second best time is now.',
  'It\'s never too late to pick up a musical instrument.',
  '2026-07-11 09:00:00', 3, 47, 'mike@gmail.com', 'skill'
),
(
  'My First Open Source Project',
  'Three years ago, I made my first contribution to an open source project, and it changed my career forever. At the time, I was a junior developer struggling with imposter syndrome. I could write code but I did not think I was good enough to contribute to the open source community. A mentor of mine encouraged me to start with something small - a documentation fix on a popular JavaScript library.\n\nThe experience was both nerve-wracking and thrilling. I remember spending hours poring over the contribution guidelines, learning how Git works in a team environment, and anxiously waiting for maintainers to review my pull request. When it was eventually merged, I felt an incredible sense of accomplishment. Having my code live in a production application used by thousands was amazing.\n\nThat first contribution opened the floodgates. Now, I maintain two open source projects of my own and over the past year alone had over 1k stars on GitHub. Most importantly, I gained the confidence to tackle big codebases and the collaboration skills that saved hours of frustration.',
  'The story of how I contributed to my first open source project.',
  '2026-07-12 09:00:00', 4, 35, 'mike@gmail.com', 'story'
),
(
  'Jazz Influences on Modern Programming',
  'It may seem like a stretch, but there is an interesting parallel between jazz and programming. Both require structured improvisation while adhering to specific rules. In jazz, musicians follow chord changes and harmonic progressions but are free to improvise within those constraints. Similarly, programmers work within a syntax and framework but can innovate and create unique solutions.\n\nThe concept of "it takes a village to raise a child" applies perfectly to open source development. Just as a jazz ensemble learns to play together and listen, open source contributors must learn the codebase, follow contribution guidelines, and coordinate with teammates. Both discipline and passion are essential elements in both worlds.\n\nRefactoring code is like rearranging a jazz standard - it requires deep understanding of the original and the courage to reinterpret it. Just as a musician plays chord changes to a tune, a developer plays a set of new changes to a legacy codebase. Sometimes I listen to Miles Davis and John Coltrane while refactoring - it gets me into the zone quite nicely.',
  'How jazz improvisation parallels creative coding.',
  '2026-07-13 09:00:00', 3, 41, 'mike@gmail.com', 'music'
);

-- =====================================================
-- 4. Blog posts - Emma Wilson
-- =====================================================
INSERT INTO `BaiViet` (`TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ThoiGianDoc`, `LuotXem`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
(
  'Working Remotely from Da Nang',
  'Da Nang has become my favorite base as a digital nomad, and it is easy to see why. The city offers a perfect blend of affordable living, reliable internet, and a vibrant co-working community. I have spent most of my time working from cafes, and there is no shortage of places with good Wi-Fi.\n\nOne of the highlights of living here is the food scene. The local cuisine is incredible and inexpensive. Starting your day with a bowl of Bun Bo Hue and a steaming bowl of Mi Quang for about $1 is almost too good to be true. The locals are friendly and there are plenty of expat communities to network with.\n\nThe only downside is the traffic when you need to get to the airport. Both city center and the beach are easily accessible by scooter, and even the mountains are a short drive away. If you are a digital nomad considering Southeast Asia, Da Nang should be at the top of your list.',
  'My experience as a digital nomad in one of Vietnam\'s best cities.',
  '2026-07-14 08:00:00', 3, 55, 'emma@gmail.com', 'story'
),
(
  'Best Productivity Tools for Remote Workers',
  'Working remotely presents unique challenges, but the right tools can make all the difference. Over the years, I have tested and refined my stack of applications that help me stay organized and productive. Here are my top picks.\n\nFirst up, Notion has been a game changer for most remote workers. Its combination of docs, spreadsheets, and databases allows for seamless organization. I also rely on Trello for task management - its Board view helps me visualize my workflow and prioritize tasks effectively.\n\nFor communication, Slack is indispensable for team chat, while Zoom and Google Meet handle video calls. Lastly, LastPass and 1Password are must-haves for secure password management. Investing in these tools will save you hours every week.',
  'Tools that help me stay organized and productive while working from anywhere.',
  '2026-07-15 10:00:00', 3, 33, 'emma@gmail.com', 'technology'
),
(
  'Morning Routines for Success',
  'The way you start your morning sets the tone for the entire day. Over the past year, I have experimented with different routines to find what works best for me. Here is my current morning routine that has transformed my productivity.\n\nI start every day with meditation. Just 10 minutes of mindfulness through a meditation app like Headspace or Calm. This helps clear my mind and reduces anxiety. After that, I spend 15 minutes journaling - writing down my goals and priorities for the day.\n\nThe biggest game changer was skipping my phone for the first 30 minutes of the day. Instead, I use that time to read a book or plan my day. This simple change has improved my focus and creativity immensely. Try it for a week and see how it feels!',
  'How changing my morning routine transformed my productivity.',
  '2026-07-16 09:00:00', 2, 28, 'emma@gmail.com', 'skill'
),
(
  'Playlists for Deep Focus Work',
  'As a remote worker and coffee addict, I need music to maintain focus during long stretches of concentrated work. Over the years, I have curated several playlists that help me enter the zone quickly and effectively.\n\nMy number one pick is Lo-Fi Study - its chill low-fidelity beats and soft melodies create the perfect background for writing and coding. Chillhop playlists are another good option, especially for coding sessions. The repetitive nature of genre-specific music helps your brain enter a flow state.\n\nFor those exercising or doing repetitive tasks, I recommend synthwave and retrowave - the beats are energetic and can help maintain momentum. The key is to find music that you enjoy but does not distract you. Add dark mode for an extra boost.',
  'Curated music playlists that help me concentrate during long coding sessions.',
  '2026-07-17 11:00:00', 2, 44, 'emma@gmail.com', 'music'
);

-- =====================================================
-- 5. Blog posts - Alex Rivera
-- =====================================================
INSERT INTO `BaiViet` (`TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ThoiGianDoc`, `LuotXem`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
(
  'Why TypeScript is the Future',
  'After working with both JavaScript and TypeScript on production projects, I am convinced that TypeScript is the future of web development. The static type system catches errors early in the development process, which saves invaluable hours of debugging time. In large codebases, this alone is a game-changer.\n\nOne of the biggest advantages of TypeScript is improved developer experience through IDE support. Auto-completion, real-time type checking, and easier refactoring make developers more productive. Above all, interfaces and type aliases provide a clear contract for the code you write, making it easier for teams to collaborate.\n\nTypeScript should be the default choice for any new large-scale project. The ecosystem is maturing quickly, and the community and tooling support have never been better. If you have not tried TypeScript yet, I highly recommend starting with a small project and seeing how it transforms your workflow.',
  'Exploring the benefits of TypeScript over JavaScript in large projects.',
  '2026-07-14 10:00:00', 3, 71, 'alex@gmail.com', 'technology'
),
(
  'Git Workflow Best Practices',
  'A good Git workflow is essential for any team - whether you are a solo developer or part of a large organization. Over the years, I have seen teams struggle because of poor branching strategies, and others thrive because of clear guidelines.\n\nThe GitFlow model works well for small to medium teams. Each developer creates a feature branch, commits their changes regularly, and submits pull requests into the main branch. One crucial practice I enforce is meaningful commit messages - they should describe what changed and why.\n\nCode reviews are just as important as the code itself. Develop a branch protection rule to require approvals before merging. Use draft PRs for earlier feedback. Regular refactoring and rebasing are essential to keep the history clean. These practices are not just rules - they foster collaboration and respect.',
  'Team collaboration strategies using Git branching and merging.',
  '2026-07-15 10:00:00', 3, 39, 'alex@gmail.com', 'skill'
),
(
  'From Bootcamp to Senior Developer',
  'Five years ago, I was working an entry-level job in a completely different field. I had no coding experience when I signed up for a coding bootcamp. It was one of the most intensive three months of my life. The curriculum covered everything from HTML and CSS to JavaScript, React, and basic database concepts.\n\nAfter graduation, I landed a junior developer position at a startup. The real learning began there. I was incredibly lucky to have great mentors who pushed me to learn best practices, write tests, and think critically about architecture. Effort in this industry is real - you get out what you put in.\n\nFive years later, I became a senior developer. The title did not come from being the best technically, but from learning to communicate, lead, and mentor others. The journey from bootcamp to senior developer took hard work, but it is a journey every developer can make with the right mindset.',
  'My 5-year journey from coding bootcamp to senior software engineer.',
  '2026-07-16 10:00:00', 4, 58, 'alex@gmail.com', 'story'
),
(
  'Coding to Lo-Fi Beats',
  'There is something magical about coding with lo-fi beats playing in the background. The genre has become so popular that there are even live streams on YouTube and Spotify dedicated solely to coding to lo-fi music. But what makes it so effective?\n\nLo-fi hip hop has a slow, steady tempo ranging from 70-90 BPM. This range coincides with the alpha waves of the human brain when in a state of focus. The repetitive nature of the beats helps your brain stay engaged without jarring you out of your flow.\n\nI personally have a Lo-Fi Girl playlist and a Chillhop playlist on rotation. When I am working on a tricky refactoring project, I switch to Electronic hyperpop or Space ambient tracks. The variety of music available for focused work nowadays means there is always something for every taste.',
  'Why lo-fi hip hop has become the soundtrack of modern programming.',
  '2026-07-17 10:00:00', 2, 67, 'alex@gmail.com', 'music'
);

-- =====================================================
-- 6. Blog posts - Lisa Park
-- =====================================================
INSERT INTO `BaiViet` (`TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ThoiGianDoc`, `LuotXem`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
(
  'Getting Started with Ableton Live',
  'Ableton Live is one of the most popular DAWs (Digital Audio Workstations) for music production. Whether you are a beginner or an experienced producer, its intuitive interface and powerful features make it an excellent choice. When you first open Ableton, it can feel overwhelming, but starting with the Session View is a great way to ease in.\n\nThe two main views in Ableton are the Arrangement View and the Session View. The Arrangement View is where you arrange musical clips on a timeline, while the Session View is where you record and edit audio. Understanding the difference between these views is crucial for beginners.\n\nThe best way to learn is to start a small project. Import a sample loop, add a drum loop, and experiment with effects. There are countless free tutorials on YouTube to help you get started. In the music production world, the only rule is that there are no rules. Just make sounds!',
  'A beginner\'s guide to one of the most popular DAWs in music production.',
  '2026-07-14 09:00:00', 3, 49, 'lisa@gmail.com', 'technology'
),
(
  'Mixing Vocals Like a Pro',
  'Achieving a clean, professional vocal mix is the gold standard in music production. After years of experimentation, I have developed a workflow that consistently produces great results. The first and most important step is recording high-quality audio in a treated space. A good microphone and an acoustic environment are worth investing in.\n\nOnce you have your raw recording, the next step is editing. Start with corrective EQ (equalization) to remove unwanted noise. Then use a compressor to control the dynamic range, making sure the performance is consistent. A light touch of reverb, delay, and chorus can add professional-sounding space and depth.\n\nThe secret sauce is in the details. Using automation on the vocal track ensures levels stay consistent throughout the song. Put your reverb very short for warmer-sounding vocals and longer for groovier tones. Always use reference tracks before and after to hear the effect of your changes. With practice, your mixes will sound professional every time.',
  'Essential techniques for achieving clean, professional vocal mixes.',
  '2026-07-15 09:00:00', 4, 36, 'lisa@gmail.com', 'skill'
),
(
  'My First Year in the Music Industry',
  'My first year as a music producer was a whirlwind of emotions. I started with a dream and a couple of sample packs, with no idea how to release my music or network with artists. The learning curve was steep, but every failure taught me something valuable.\n\nOne of the biggest lessons I learned was the importance of networking. Showing up at local open mic nights, connecting with singers on social media, and collaborating with other producers opened so many doors. The music industry is built on relationships, not just talent. It is about who you know and who knows you.\n\nToday, I am working on my third album, and I have a small but growing client base. The journey was not easy, but it was worth every second. My advice to aspiring producers is simple: do not wait for permission. Release the fear. Make the first move. The rest will follow.',
  'Lessons learned and challenges faced as a new music producer.',
  '2026-07-16 09:00:00', 3, 42, 'lisa@gmail.com', 'story'
),
(
  'The Evolution of Electronic Music',
  'Electronic music has come a long way since its origins in the 1970s. From the pioneering work of Kraftwerk and Tangerine Dream, the genre evolved through the dance era of the 1980s, the rave scene of the 1990s, and the electronic boom of the 2000s. Each decade brought new sounds and pushed the boundaries of what music could be.\n\nThe 1980s brought the rise of house and techno music, with artists like Depeche Mode and Juno Reactor popularizing the genre. The 1990s witnessed the birth of rave music, with The Prodigy and The Chemical Brothers revolutionizing the scene. Meanwhile, samplers and drum machines became essential tools that democratized production.\n\nToday, electronic music is everywhere. It influences pop, EDM, IDM, and mainstream artists alike. Genres like dubstep, techno, house, trap, and experimental have all found their place. With AI and machine learning becoming part of the creative process, the future of electronic music is more exciting than ever.',
  'Tracing the history of electronic music from the 70s to today.',
  '2026-07-17 09:00:00', 4, 53, 'lisa@gmail.com', 'music'
);

-- =====================================================
-- 7. Blog posts - Admin
-- =====================================================
INSERT INTO `BaiViet` (`TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ThoiGianDoc`, `LuotXem`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
(
  'Platform Updates and New Features',
  'We are excited to announce the latest updates to our blogging platform. Over the past few months, our team has been hard at work improving the user experience. We have overhauled the editor interface, making it more intuitive and powerful. The new version now supports drag and drop functionality for images, and a new set of story templates that make your posts stand out.\n\nAnother major update is the optimization of mobile responsiveness. Our new theme is fully adaptive, ensuring your blog looks great on any device. We have also improved page load times by optimizing image processing. Load times have dropped by over 40% compared to the previous version.\n\nAnd do not forget the new social sharing features. You can now share your posts directly to Twitter and LinkedIn. We have also added a built-in analytics dashboard to help you track readership and engagement. As always, we welcome your feedback and feature suggestions.',
  'Announcing the latest improvements to our blogging platform.',
  '2026-07-18 08:00:00', 3, 88, 'admin@admin.com', 'technology'
),
(
  'Community Guidelines',
  'We believe in building a vibrant and inclusive community of writers and readers. To keep our platform positive and respectful, we have established community guidelines that apply to all users. These guidelines are designed to protect both readers and contributors.\n\nFirst and foremost, be respectful to others. This includes respecting different opinions, avoiding personal attacks, and not spamming. Any content that is harassing, threatening, or promotes violence will be removed immediately. Protecting personal information is also critical - never share private information about others.\n\nHelp us maintain our community. If you see something that violates these guidelines, report it to our moderation team. Together, we can maintain a warm and inspiring environment for everyone. Thank you for being a part of BloggerZ!',
  'Important rules and best practices for all community members.',
  '2026-07-18 10:00:00', 2, 76, 'admin@admin.com', 'skill'
),
(
  'Welcome to BloggerZ',
  'Welcome to BloggerZ! This platform was born from a simple idea: to create a space where people from all walks of life can share their stories, knowledge, and perspectives. Whether you are a traveler, a developer, a musician, or simply someone with a story to tell, this is the place for you.\n\nOur mission is to connect writers worldwide. In an age where information is abundant, we believe in the power of personal, authentic storytelling. Every voice deserves to be heard, and that is exactly why we built this platform. We offer a simple, beautiful reading experience with various categories to explore.\n\nThank you for being here. We are so excited to see what you will create. Start by browsing through our categories, read some posts, and when you are ready, start sharing your own world. Welcome aboard!',
  'The story behind BloggerZ and our mission to connect writers worldwide.',
  '2026-07-18 14:00:00', 3, 95, 'admin@admin.com', 'story'
),
(
  'Background Music for Writing',
  'Finding the right music to write to can make all the difference. As a writer, I constantly search for music that inspires me without distracting me. Over the years, I have curated a collection of ambient tracks that are perfect for focused writing sessions.\n\nMy personal favorite is ambient piano - heavy on piano, light orchestration, and cello. It creates a calm, focused atmosphere without overwhelming your senses. For long-form projects, ambient techno and drone music are also excellent choices. The slow, evolving beats and layered instrumental textures keep the mind engaged.\n\nI encourage you to create your own writing playlist. The right soundtrack can transform your writing session from a chore into a journey of inspiration. Try different genres and see what resonates with your internal rhythm.',
  'Curated collection of ambient tracks perfect for focused writing.',
  '2026-07-19 10:00:00', 3, 82, 'admin@admin.com', 'music'
),
(
  'Tips for New Bloggers',
  'Starting a blog can be both exciting and overwhelming. As someone who has spent years in this community, I have put together some essential tips to help new bloggers get started. The first tip is to find your niche and create content consistently. It is better to be a master of one niche than a jack of all trades.\n\nQuality over quantity - this is most important. It is better to publish one well-researched, thoughtful post per week than five hastily written ones. Invest in a good hosting provider for better load times. Engage with your readers through comments and social media to build loyalty and community.\n\nDo not be afraid to share your unique voice. The blogging world is crowded, but no one has your exact perspective. Write about what you genuinely care about. Your authenticity will attract the right audience.',
  'Essential advice for anyone starting their blogging journey.',
  '2026-07-20 10:00:00', 3, 91, 'admin@admin.com', 'skill'
);

-- =====================================================
-- 8. Reviews - 1 per user (including admin)
-- =====================================================
INSERT INTO `Reviews` (`HoTen`, `Email`, `NoiDung`) VALUES
('Sarah Johnson', 'sarah@gmail.com', 'I absolutely love this platform! The interface is clean and intuitive, and I love how easy it is to share my travel stories with the world. The community here is incredibly supportive. Highly recommended for any aspiring blogger!'),
('Mike Chen', 'mike@gmail.com', 'BloggerZ has been a fantastic platform for sharing my technical knowledge and musical journey. The editor is powerful yet simple to use. I appreciate the focus on quality content and the supportive community of writers.'),
('Emma Wilson', 'emma@gmail.com', 'As a digital nomad, I have tried many blogging platforms and this is by far the best. The mobile experience is great and the categories make it easy to discover new content. Keep up the amazing work!'),
('Alex Rivera', 'alex@gmail.com', 'The best blogging platform I have ever used. The code formatting feature is perfect for technical blogs, and the reading experience is clean and distraction-free. Highly recommend from a developer!'),
('Lisa Park', 'lisa@gmail.com', 'Finally found a platform that takes music blogging seriously. The ability to share production tips and connect with fellow musicians has been invaluable. Great community of creative minds!'),
('Admin', 'admin@admin.com', 'We are constantly working to improve BloggerZ and your feedback drives our development. Thank you to everyone who contributes to making this community special. Together we are building something amazing!');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
