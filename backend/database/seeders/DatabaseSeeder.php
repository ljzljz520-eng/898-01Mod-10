<?php

namespace Database\Seeders;

use App\Models\KnowledgeCard;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 创建管理员（如果不存在）
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@forum.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'status' => 1,
            ]
        );

        // 创建普通用户（如果不存在）
        $user1 = User::firstOrCreate(
            ['username' => 'user1'],
            [
                'email' => 'user1@forum.com',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'status' => 1,
            ]
        );

        $user2 = User::firstOrCreate(
            ['username' => 'user2'],
            [
                'email' => 'user2@forum.com',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'status' => 1,
            ]
        );

        // 创建更多用户
        $users = [$user1, $user2];
        for ($i = 3; $i <= 10; $i++) {
            $users[] = User::firstOrCreate(
                ['username' => "user{$i}"],
                [
                    'email' => "user{$i}@forum.com",
                    'password' => Hash::make('123456'),
                    'role' => 'user',
                    'status' => 1,
                ]
            );
        }

        // 帖子标题和内容模板
        $titles = [
            '欢迎来到学习交流论坛',
            '如何高效学习编程？',
            '推荐一些优质的学习资源',
            'Vue.js 3.0 新特性详解',
            'Laravel 最佳实践分享',
            '前端性能优化技巧',
            '数据库设计原则',
            'RESTful API 设计规范',
            'Docker 容器化部署指南',
            'Git 工作流最佳实践',
            'TypeScript 类型系统深入理解',
            'React Hooks 使用心得',
            'Node.js 异步编程模式',
            '微服务架构设计思考',
            '算法与数据结构学习路径',
            '系统设计面试准备',
            '代码重构技巧分享',
            '单元测试编写指南',
            'CI/CD 持续集成实践',
            '网络安全防护措施',
            'Linux 系统管理基础',
            'Redis 缓存使用场景',
            '消息队列选型对比',
            '分布式系统一致性',
            '高并发系统设计',
            '机器学习入门教程',
            '深度学习框架对比',
            '数据可视化工具推荐',
            '项目管理工具使用',
            '团队协作最佳实践',
            '技术文档编写规范',
            '代码审查要点总结',
            '技术债务管理方法',
            '性能监控工具介绍',
            '日志分析实践',
            '错误追踪系统搭建',
            'API 网关设计',
            '服务网格架构',
            '云原生应用开发',
            'Serverless 架构实践',
            'GraphQL vs REST',
            'WebSocket 实时通信',
            'PWA 渐进式 Web 应用',
            '移动端适配方案',
            '响应式设计技巧',
            '无障碍访问优化',
            'SEO 优化策略',
            '网站安全加固',
            'CDN 加速配置',
            '图片优化技巧',
        ];

        $categories = ['general', 'tech', 'study', 'question', 'broadband', 'school', 'parking', 'renovation'];
        $contents = [
            '这是一个学习交流的论坛，欢迎大家在这里分享知识、讨论问题、共同进步！论坛支持发布主题、回复讨论等功能，希望大家能够积极参与，营造良好的学习氛围。',
            '想和大家讨论一下学习编程的方法和技巧。个人觉得实践很重要，多写代码、多思考、多总结。',
            '分享一些我觉得不错的学习网站和课程，希望对大家有帮助。',
            '最近在学习 Vue.js 3.0，发现了很多新特性和改进，想和大家分享一下。Composition API 的使用体验非常好，性能也有明显提升。',
            '在使用 Laravel 开发项目时，总结了一些最佳实践，包括路由设计、模型关系、中间件使用等。',
            '前端性能优化是一个持续的过程，可以从代码分割、懒加载、缓存策略等多个方面入手。',
            '良好的数据库设计是系统稳定运行的基础，需要遵循范式设计原则，同时考虑实际业务需求。',
            'RESTful API 设计需要遵循统一的规范，包括资源命名、HTTP 方法使用、状态码选择等。',
            'Docker 容器化可以大大简化部署流程，提高开发效率，这里分享一些实践经验。',
            'Git 工作流的选择对团队协作很重要，可以根据团队规模选择合适的流程。',
        ];

        // 创建帖子：按标题去重，避免重复主题（多次执行 Seeder 也不会重复）
        $topics = [];
        foreach ($titles as $index => $title) {
            $user = $users[array_rand($users)];
            $category = $categories[array_rand($categories)];
            // 基于模板内容 + 标题生成，确保每个主题内容也不重复
            $baseContent = $contents[$index % count($contents)];
            $content = $baseContent.'（本主题：'.$title.'）';
            
            // 为前3条帖子设置置顶
            $isPinned = $index < 3 ? 1 : 0;
            
            // 随机生成浏览量和回复数
            $viewCount = rand(10, 500);
            $replyCount = rand(0, 20);
            
            // 以 title 作为唯一键，防止重复主题；若已存在则不再新建
            $topic = Topic::firstOrCreate(
                ['title' => $title],
                [
                    'user_id' => $user->id,
                    'content' => $content,
                    'category' => $category,
                    'view_count' => $viewCount,
                    'reply_count' => $replyCount,
                    'is_pinned' => $isPinned,
                    'status' => 1,
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                ]
            );
            
            $topics[] = $topic;
            
            // 仅在新建主题时创建回复，避免多次 Seeder 时重复堆叠回复
            if ($topic->wasRecentlyCreated) {
                $replyCount = min($replyCount, 10); // 最多10条回复
                $replyContents = [
                    '很好的分享，学到了很多！',
                    '感谢楼主的详细讲解，受益匪浅。',
                    '我也遇到过类似的问题，我的解决方法是...',
                    '这个思路很不错，值得借鉴。',
                    '补充一点，还可以考虑...',
                    '同意楼主的观点，实践确实很重要。',
                    '期待更多类似的分享！',
                    '请问有没有相关的代码示例？',
                    '这个方案在实际项目中效果如何？',
                    '感谢分享，收藏了！',
                ];

                // 打乱顺序后按需取用，保证同一主题下回复内容不重复
                shuffle($replyContents);

                for ($j = 0; $j < $replyCount; $j++) {
                    $replyUser = $users[array_rand($users)];
                    Reply::create([
                        'topic_id' => $topic->id,
                        'user_id' => $replyUser->id,
                        'content' => $replyContents[$j],
                        'status' => 1,
                        'created_at' => $topic->created_at->addHours(rand(1, 48)),
                    ]);
                }
            }
        }

        $businessPosts = [
            [
                'category' => 'broadband',
                'title' => '2024年小区宽带办理全攻略（电信/联通/移动对比）',
                'content' => '最近搬新家，研究了一下三大运营商的宽带套餐，给大家分享一下：

一、套餐对比：
1. 电信：100M/年360元，200M/年600元，初装费100元
2. 联通：100M/年300元，200M/年540元，初装费50元
3. 移动：100M/年240元，200M/年420元，绑定手机号免费

二、办理流程：
1. 先打客服电话确认小区是否有资源
2. 带身份证去营业厅办理，或者线上预约
3. 师傅上门安装，一般1-3个工作日

三、注意事项：
1. 一定要问清楚合约期，提前取消要付违约金
2. 安装时测试一下实际网速，避免被坑
3. 不想要的增值服务一定要取消',
                'card_title' => '小区宽带办理全攻略（2024版）',
                'card_summary' => "一、套餐对比\n1. 电信：100M/年360元，200M/年600元，初装费100元\n2. 联通：100M/年300元，200M/年540元，初装费50元\n3. 移动：100M/年240元，200M/年420元，绑定手机号免费\n\n二、办理流程\n1. 先打客服电话确认小区是否有资源\n2. 带身份证去营业厅办理，或者线上预约\n3. 师傅上门安装，一般1-3个工作日\n\n三、注意事项\n1. 一定要问清楚合约期，提前取消要付违约金\n2. 安装时测试一下实际网速，避免被坑\n3. 不想要的增值服务一定要取消",
                'card_tags' => '电信,联通,移动,宽带,套餐',
                'expire_days' => 365,
            ],
            [
                'category' => 'school',
                'title' => '2024年小学入学材料准备清单（过来人经验）',
                'content' => '孩子今年要上小学了，提前整理了一下需要的材料，给大家参考：

一、必备材料：
1. 户口本原件及复印件（首页、户主页、孩子页、父母页）
2. 房产证原件及复印件（或购房合同+发票）
3. 父母身份证原件及复印件
4. 孩子出生证明原件及复印件
5. 儿童预防接种证（需要开查验证明）
6. 近期一寸免冠照片3张

二、特殊情况：
1. 集体户口：需要提供无房证明
2. 租房：需要提供租房合同、房东房产证、备案证明
3. 外来务工：需要提供社保缴纳证明、居住证

三、时间节点：
1. 4月初：信息采集
2. 5月初：材料审核
3. 6月初：入学通知

四、注意事项：
1. 所有复印件用A4纸，按顺序整理
2. 提前1个月去社区医院开接种证明
3. 房产证一定要是父母名下的',
                'card_title' => '小学入学材料准备清单',
                'card_summary' => "一、必备材料\n1. 户口本原件及复印件（首页、户主页、孩子页、父母页）\n2. 房产证原件及复印件（或购房合同+发票）\n3. 父母身份证原件及复印件\n4. 孩子出生证明原件及复印件\n5. 儿童预防接种证（需要开查验证明）\n6. 近期一寸免冠照片3张\n\n二、特殊情况\n1. 集体户口：需要提供无房证明\n2. 租房：需要提供租房合同、房东房产证、备案证明\n3. 外来务工：需要提供社保缴纳证明、居住证\n\n三、时间节点\n1. 4月初：信息采集\n2. 5月初：材料审核\n3. 6月初：入学通知\n\n四、注意事项\n1. 所有复印件用A4纸，按顺序整理\n2. 提前1个月去社区医院开接种证明\n3. 房产证一定要是父母名下的",
                'card_tags' => '小学,入学,材料,学区,户口',
                'expire_days' => 180,
            ],
            [
                'category' => 'parking',
                'title' => '小区停车证办理流程及材料要求',
                'content' => '刚办了小区停车证，给大家分享一下流程：

一、办理条件：
1. 必须是业主或租客
2. 车辆必须在本人名下
3. 无拖欠物业费记录

二、所需材料：
1. 业主：房产证、身份证、行驶证、驾驶证
2. 租客：租房合同、房东身份证、本人身份证、行驶证、驾驶证
3. 车辆交强险保单（在有效期内）

三、办理流程：
1. 去物业前台填写申请表
2. 提交材料审核（一般3个工作日）
3. 审核通过后缴费
4. 领取停车证，录入车牌识别系统

四、收费标准：
1. 地面：150元/月，1500元/年
2. 地下：300元/月，3000元/年
3. 临时停车：5元/小时，30元封顶

五、注意事项：
1. 一辆车只能办一个证
2. 换车需要重新办理
3. 到期前15天续费',
                'card_title' => '小区停车证办理指南',
                'card_summary' => "一、办理条件\n1. 必须是业主或租客\n2. 车辆必须在本人名下\n3. 无拖欠物业费记录\n\n二、所需材料\n1. 业主：房产证、身份证、行驶证、驾驶证\n2. 租客：租房合同、房东身份证、本人身份证、行驶证、驾驶证\n3. 车辆交强险保单（在有效期内）\n\n三、办理流程\n1. 去物业前台填写申请表\n2. 提交材料审核（一般3个工作日）\n3. 审核通过后缴费\n4. 领取停车证，录入车牌识别系统\n\n四、收费标准\n1. 地面：150元/月，1500元/年\n2. 地下：300元/月，3000元/年\n3. 临时停车：5元/小时，30元封顶",
                'card_tags' => '停车证,物业,车牌识别,停车费',
                'expire_days' => 720,
            ],
            [
                'category' => 'renovation',
                'title' => '新房装修流程详解（从收房到入住）',
                'content' => '刚装完一套房子，整理了一下完整流程，供大家参考：

一、准备阶段（1-2周）
1. 收房验房：检查墙面、地面、门窗、水电
2. 确定装修风格和预算
3. 找装修公司或施工队，对比报价

二、设计阶段（2-3周）
1. 量房、出平面图
2. 确定效果图和施工图
3. 签订装修合同

三、施工阶段（2-3个月）
1. 主体拆改：拆墙、砌墙
2. 水电改造：开槽、布管、穿线
3. 泥工：贴砖、防水、找平
4. 木工：吊顶、衣柜、门窗套
5. 油漆：墙面批灰、刷漆
6. 安装：橱柜、地板、木门、灯具

四、软装阶段（1-2周）
1. 家具进场
2. 家电安装
3. 窗帘、装饰品

五、注意事项：
1. 一定要找正规装修公司，签详细合同
2. 水电改造要拍照留底
3. 每阶段完工后要验收
4. 装修后至少通风3个月再入住',
                'card_title' => '新房装修流程详解（从收房到入住）',
                'card_summary' => "一、准备阶段（1-2周）\n1. 收房验房：检查墙面、地面、门窗、水电\n2. 确定装修风格和预算\n3. 找装修公司或施工队，对比报价\n\n二、设计阶段（2-3周）\n1. 量房、出平面图\n2. 确定效果图和施工图\n3. 签订装修合同\n\n三、施工阶段（2-3个月）\n1. 主体拆改：拆墙、砌墙\n2. 水电改造：开槽、布管、穿线\n3. 泥工：贴砖、防水、找平\n4. 木工：吊顶、衣柜、门窗套\n5. 油漆：墙面批灰、刷漆\n6. 安装：橱柜、地板、木门、灯具\n\n四、软装阶段（1-2周）\n1. 家具进场\n2. 家电安装\n3. 窗帘、装饰品\n\n五、注意事项\n1. 一定要找正规装修公司，签详细合同\n2. 水电改造要拍照留底\n3. 每阶段完工后要验收\n4. 装修后至少通风3个月再入住",
                'card_tags' => '装修,流程,收房,施工,验房',
                'expire_days' => 730,
            ],
        ];

        foreach ($businessPosts as $post) {
            $topic = Topic::firstOrCreate(
                ['title' => $post['title']],
                [
                    'user_id' => $admin->id,
                    'content' => $post['content'],
                    'category' => $post['category'],
                    'view_count' => rand(50, 500),
                    'reply_count' => rand(5, 30),
                    'status' => 1,
                    'created_at' => now()->subDays(rand(30, 180)),
                ]
            );

            if ($topic->wasRecentlyCreated) {
                for ($j = 0; $j < min($topic->reply_count, 10); $j++) {
                    $replyUser = $users[array_rand($users)];
                    Reply::create([
                        'topic_id' => $topic->id,
                        'user_id' => $replyUser->id,
                        'content' => '感谢分享，正好需要！',
                        'status' => 1,
                        'created_at' => $topic->created_at->addHours(rand(1, 48)),
                    ]);
                }
            }

            KnowledgeCard::firstOrCreate(
                ['topic_id' => $topic->id],
                [
                    'moderator_id' => $admin->id,
                    'title' => $post['card_title'],
                    'summary' => $post['card_summary'],
                    'category' => $post['category'],
                    'tags' => $post['card_tags'],
                    'expire_date' => now()->addDays($post['expire_days']),
                    'last_reviewed_at' => now(),
                    'status' => KnowledgeCard::STATUS_ACTIVE,
                    'view_count' => rand(100, 1000),
                    'created_at' => now()->subDays(rand(10, 60)),
                ]
            );
        }
    }
}
