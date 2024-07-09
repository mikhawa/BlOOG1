<?php

namespace model\Manager;

use model\Mapping\{ArticleMapping, UserMapping, CategoryMapping, TagMapping};
use model\OurPDO;
use model\Interface\{InterfaceManager, InterfaceSlugManager};

/**
 * Class ArticleManager
 *
 * Gère les articles en base de données.
 * Implémente les méthodes de InterfaceManager et InterfaceSlugManager.
 */
class ArticleManager implements InterfaceManager, InterfaceSlugManager
{
    private OurPDO $db;

    public function __construct(OurPDO $pdo)
    {
        $this->db = $pdo;
    }

    private function fetchArticles(string $query, array $params = []): ?array
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        if ($stmt->rowCount() == 0) return null;

        $articlesData = $stmt->fetchAll();
        $stmt->closeCursor();

        return $this->mapArticles($articlesData);
    }

    private function mapArticles(array $articlesData): array
    {
        $articles = [];
        foreach ($articlesData as $data) {
            $user = $data['user_login'] ? new UserMapping($data) : null;
            $categories = $data['category_id'] ? $this->mapCategories($data) : null;
            $tags = $data['tag_slug'] ?? null ? $this->mapTags($data) : null;

            $article = new ArticleMapping($data);
            $article->setUser($user);
            $article->setCategories($categories);
            $article->setTags($tags);
            $articles[] = $article;
        }
        return $articles;
    }

    private function mapCategories(array $data): array
    {
        $categories = [];
        $ids = explode(",", $data['category_id']);
        $names = explode("|||", $data['category_name']);
        $slugs = explode("|||", $data['category_slug']);

        for ($i = 0; $i < count($ids); $i++) {
            $categories[] = new CategoryMapping([
                'category_id' => $ids[$i],
                'category_name' => $names[$i],
                'category_slug' => $slugs[$i],
            ]);
        }
        return $categories;
    }

    private function mapTags(array $data): array
    {
        $tags = [];
        $slugs = explode("|||", $data['tag_slug']);

        foreach ($slugs as $slug) {
            $tags[] = new TagMapping(['tag_slug' => $slug]);
        }
        return $tags;
    }

    public function selectAll(): ?array
    {
        return $this->fetchArticles("SELECT * FROM article");
    }

    public function selectAllArticleHomepage(): ?array
    {
        $query = "
            SELECT a.article_id, a.article_title, 
                   SUBSTRING_INDEX(a.article_text,' ', 30) as article_text,
                   a.article_slug, a.article_date_publish, 
                   u.user_id, u.user_login, u.user_full_name,
                   GROUP_CONCAT(c.category_id) as category_id, 
                   GROUP_CONCAT(c.category_name SEPARATOR '|||') as category_name, 
                   GROUP_CONCAT(c.category_slug SEPARATOR '|||') as category_slug,
                   (SELECT COUNT(*) FROM comment c WHERE a.article_id = c.article_article_id) as comment_count
            FROM article a
            INNER JOIN user u ON u.user_id = a.user_user_id
            LEFT JOIN article_has_category ahc ON ahc.article_article_id = a.article_id
            LEFT JOIN category c ON c.category_id = ahc.category_category_id
            WHERE a.article_is_published = 1
            GROUP BY a.article_id
            ORDER BY a.article_date_publish DESC
        ";
        return $this->fetchArticles($query);
    }

    public function selectAllArticleByCategorySlug(string $slug): ?array
    {
        $query = "
            SELECT a.article_id, a.article_title, 
                   SUBSTRING_INDEX(a.article_text,' ', 30) as article_text,
                   a.article_slug, a.article_date_publish, 
                   u.user_id, u.user_login, u.user_full_name,
                   GROUP_CONCAT(c2.category_id) as category_id, 
                   GROUP_CONCAT(c2.category_name SEPARATOR '|||') as category_name, 
                   GROUP_CONCAT(c2.category_slug SEPARATOR '|||') as category_slug,
                   (SELECT COUNT(*) FROM comment c WHERE a.article_id = c.article_article_id) as comment_count
            FROM article a
            INNER JOIN user u ON u.user_id = a.user_user_id
            LEFT JOIN article_has_category ahc ON ahc.article_article_id = a.article_id
            LEFT JOIN category c ON c.category_id = ahc.category_category_id
            LEFT JOIN article_has_category ahc2 ON ahc2.article_article_id = a.article_id
            LEFT JOIN category c2 ON c2.category_id = ahc2.category_category_id
            WHERE a.article_is_published = 1 AND c.category_slug = :slug
            GROUP BY a.article_id
            ORDER BY a.article_date_publish DESC
        ";
        return $this->fetchArticles($query, ['slug' => $slug]);
    }

    public function selectAllArticleByTagSlug(string $slug): ?array
    {
        $query = "
            SELECT a.article_id, a.article_title, 
                   SUBSTRING_INDEX(a.article_text,' ', 30) as article_text,
                   a.article_slug, a.article_date_publish, 
                   u.user_id, u.user_login, u.user_full_name,
                   GROUP_CONCAT(c.category_id) as category_id, 
                   GROUP_CONCAT(c.category_name SEPARATOR '|||') as category_name, 
                   GROUP_CONCAT(c.category_slug SEPARATOR '|||') as category_slug,
                   (SELECT COUNT(*) FROM comment c WHERE a.article_id = c.article_article_id) as comment_count
            FROM article a
            INNER JOIN user u ON u.user_id = a.user_user_id
            INNER JOIN tag_has_article tha ON tha.article_article_id = a.article_id
            INNER JOIN tag t ON t.tag_id = tha.tag_tag_id
            LEFT JOIN article_has_category ahc ON ahc.article_article_id = a.article_id
            LEFT JOIN category c ON c.category_id = ahc.category_category_id
            WHERE a.article_is_published = 1 AND t.tag_slug = :slug
            GROUP BY a.article_id
            ORDER BY a.article_date_publish DESC
        ";
        return $this->fetchArticles($query, ['slug' => $slug]);
    }

    public function selectAllArticleByUserId(int $id): ?array
    {
        $query = "
            SELECT a.article_id, a.article_title, 
                   SUBSTRING_INDEX(a.article_text,' ', 30) as article_text,
                   a.article_slug, a.article_date_publish, 
                   u.user_id, u.user_login, u.user_full_name,
                   GROUP_CONCAT(c.category_id) as category_id, 
                   GROUP_CONCAT(c.category_name SEPARATOR '|||') as category_name, 
                   GROUP_CONCAT(c.category_slug SEPARATOR '|||') as category_slug,
                   (SELECT COUNT(*) FROM comment c WHERE a.article_id = c.article_article_id) as comment_count
            FROM article a
            INNER JOIN user u ON u.user_id = a.user_user_id
            LEFT JOIN article_has_category ahc ON ahc.article_article_id = a.article_id
            LEFT JOIN category c ON c.category_id = ahc.category_category_id
            WHERE a.article_is_published = 1 AND a.user_user_id = :id
            GROUP BY a.article_id
            ORDER BY a.article_date_publish DESC
        ";
        return $this->fetchArticles($query, ['id' => $id]);
    }

    public function selectOneById(int $id): object
    {
        // TODO: Implement selectOneById() method.
    }

    public function insert(object $object): void
    {
        // TODO: Implement insert() method.
    }

    public function update(object $object): void
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function selectOneBySlug(string $slug): ?ArticleMapping
    {
        $query = "
            SELECT a.*, 
                   u.user_id, u.user_login, u.user_full_name,
                   GROUP_CONCAT(c.category_id) as category_id, 
                   GROUP_CONCAT(c.category_name SEPARATOR '|||') as category_name, 
                   GROUP_CONCAT(c.category_slug SEPARATOR '|||') as category_slug,
                   (SELECT GROUP_CONCAT(t.tag_slug SEPARATOR '|||')
                    FROM tag t
                    INNER JOIN tag_has_article tha ON tha.article_article_id = a.article_id
                    WHERE t.tag_id = tha.tag_tag_id
                    GROUP BY a.article_id
                    ORDER BY t.tag_slug ASC) as tag_slug
            FROM article a
            INNER JOIN user u ON u.user_id = a.user_user_id
            LEFT JOIN article_has_category ahc ON ahc.article_article_id = a.article_id
            LEFT JOIN category c ON c.category_id = ahc.category_category_id
            WHERE a.article_is_published = 1 AND a.article_slug = :slug
            GROUP BY a.article_id
        ";
        $articles = $this->fetchArticles($query, ['slug' => $slug]);
        return $articles ? $articles[0] : null;
    }
}
