<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeAndCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_like_toggle_requires_authentication(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->postJson(route('blogs.like', $blog));

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_like_and_unlike_a_blog(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $response = $this->actingAs($user)->postJson(route('blogs.like', $blog));

        $response->assertStatus(200)
            ->assertJson([
                'liked' => true,
                'likes_count' => 1,
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'blog_id' => $blog->id,
        ]);

        // Unlike
        $response = $this->actingAs($user)->postJson(route('blogs.like', $blog));

        $response->assertStatus(200)
            ->assertJson([
                'liked' => false,
                'likes_count' => 0,
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'blog_id' => $blog->id,
        ]);
    }

    public function test_comment_creation_requires_authentication(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->postJson(route('comments.store', $blog), [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_post_comment(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $response = $this->actingAs($user)->postJson(route('comments.store', $blog), [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'comments_count' => 1,
                'is_reply' => false,
            ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'blog_id' => $blog->id,
            'comment' => 'This is a test comment.',
            'parent_id' => null,
        ]);
    }

    public function test_authenticated_user_can_reply_to_comment(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();
        $comment = Comment::factory()->create([
            'blog_id' => $blog->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('comments.store', $blog), [
            'comment' => 'This is a reply.',
            'parent_id' => $comment->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'comments_count' => 2,
                'is_reply' => true,
                'parent_id' => $comment->id,
            ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'blog_id' => $blog->id,
            'comment' => 'This is a reply.',
            'parent_id' => $comment->id,
        ]);
    }

    public function test_comment_update_requires_authentication(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->putJson(route('comments.update', $comment), [
            'comment' => 'Updated comment.',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_their_own_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson(route('comments.update', $comment), [
            'comment' => 'Updated comment text.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'comment' => 'Updated comment text.',
            ]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'comment' => 'Updated comment text.',
        ]);
    }

    public function test_user_cannot_update_someone_elses_comment(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->putJson(route('comments.update', $comment), [
            'comment' => 'Trying to update.',
        ]);

        $response->assertStatus(403);
    }

    public function test_comment_delete_requires_authentication(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson(route('comments.destroy', $comment));

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_delete_their_own_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson(route('comments.destroy', $comment));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_user_cannot_delete_someone_elses_comment_unless_admin(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->deleteJson(route('comments.destroy', $comment));

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->deleteJson(route('comments.destroy', $comment));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
