<?php

class Post extends User
{
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function posts($user_id, $profileId, $num)
    {
        $userdata = $this->userData($user_id);

        $stmt = $this->pdo->prepare("SELECT * FROM users LEFT JOIN profile ON users.user_id = profile.userId LEFT JOIN post ON post.userId = users.user_id WHERE post.userId = :user_id ORDER BY post.postedOn DESC LIMIT :num");
        $stmt->bindParam(":user_id", $profileId, PDO::PARAM_INT);
        $stmt->bindParam(":num", $num, PDO::PARAM_INT);
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($posts as $post) {

            $main_react = $this->main_react($user_id, $post->post_id);
            $reat_max_show = $this->react_max_show($post->post_id);
            $main_react_count = $this->main_react_count($post->post_id);
?>
            <!-- Profile Timeline -->
            <div class="profile-timeline">
                <div class="news-feed-comp">
                    <div class="news-feed-text">
                        <div class="nf-1">
                            <div class="nf-1-left">
                                <div class="nf-pro-pic">
                                    <a href="<?php echo BASE_URL . $post->userlink; ?>"></a>
                                    <img src="<?php echo BASE_URL . $post->profilePic; ?>" class="pro-pic">
                                </div>
                                <div class="nf-pro-name-time">
                                    <div class="nf-pro-name">
                                        <a href="<?php echo BASE_URL . $post->userlink; ?>" class="nf-pro-name">
                                            <?php echo '' . $post->firstName . ' ' . $post->lastName; ?>
                                        </a>
                                    </div>
                                    <div class="nf-pro-time-privacy">
                                        <div class="nf-pro-time">
                                            <?php echo $this->timeAgo($post->postedOn); ?>
                                        </div>
                                        <div class="np-pro-privacy"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="nf-1-right">
                                <div class="nf-1-right-dott">
                                    <div class="post-option" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $post->postBy; ?>">...</div>
                                    <div class="post-option-details-container"></div>
                                </div>
                            </div>
                        </div>
                        <div class="nf-2">
                            <div class="nf-2-text" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $user_id; ?>" data-profilePic="<?php echo $post->profilePic; ?>">
                                <?php echo $post->post; ?>
                            </div>
                            <div class="nf-2-img" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $user_id; ?>">
                                <?php
                                $imgJson = json_decode($post->postImage);
                                $count = 0;
                                for ($i = 0; $i < count($imgJson); $i++) {
                                    echo '<div class="post-img-box" data-postimgid="' . $post->id . '" style="max-height:400px; overflow:hidden;"><img src="' . BASE_URL . $imgJson['' . $count++ . '']->imageName . '" class="postImage" style="width: 100%; cursor: pointer;" data-userid="' . $user_id . '" data-postid="' . $post->post_id . '" data-profileid="' . $profileId . '"></div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="nf-3">
                            <div class="react-comment-count-wrap" style="width: 100%; display:flex; justify-content: space-between; align-items:center;">
                                <div class="react-count-wrap align-middle">
                                    <div class="nf-3-react-icon">
                                        <div class="react-inst-img align-middle">
                                            <?php foreach ($reat_max_show as $react_max) {
                                                echo '<img class="' . $react_max->reacType . '-max-show" src="assets/immage/react/' . $react_max->reactType . '.png" alt="" style="height: 15px; width: 15px; margin-right:2px; cursor:pointer;">';
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="nf-3-react-username">
                                        <?php if ($main_react_count->maxreact == '0') {
                                        } else {
                                            echo $main_react_count->maxreact;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nf-4">
                            <div class="like-action-wrap" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php $user_id ?>" style="position: relative;">
                                <div class="react-bundle-wrap">

                                </div>
                                <div class="like-action ra">
                                    <?php if (empty($main_react)) { ?>
                                        <div class="like-action-icon">
                                            <img src="assets/image/likeAction.jpg" alt="">
                                        </div>
                                        <div class="like-action-text">
                                            <span>Like</span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="like-action-icon" style="display: flex; align-items:center;">
                                            <img src="assets/image/react/<?php echo $main_react->reactType; ?>.png" alt="" class="reactIconSize" style="margin-top: 0;">
                                            <div class="like-action-text">
                                                <span class="<?php echo $main_react->reactType; ?>-color"><?php echo $main_react->reactType; ?></span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="comment-action ra">
                                <div class="comment-action-icon">
                                    <img src="assets/image/commentAction.jpg" alt="">
                                </div>
                                <div class="comment-action-text">
                                    <div class="comment-count-text-wrap">
                                        <div class="comment-text">comment</div>
                                    </div>
                                </div>
                            </div>
                            <div class="share-action ra" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $user_id; ?>" data-profilePic="<?php echo $post->profilePic; ?>">
                                <div class="share-action-icon">
                                    <img src="assets/image/shareAction.jpg" alt="">
                                </div>
                                <div class="share-action-text">share</div>
                            </div>
                        </div>
                        <div class="nf-5"></div>
                    </div>
                    <div class="news-feed-photo"></div>

                </div>
            </div>

<?php
        }
    }

    public function postUpd($user_id, $post_id, $editText)
    {
        $stmt = $this->pdo->prepare('UPDATE post SET post = :editText WHERE post_id = :post_id AND userId = :user_id');
        $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":editText", $editText, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function main_react($userid, $postid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `react` WHERE `reactBy` = :user_id AND `reactOn` = :postid AND `reactCommentOn`='0' AND `reactReplyOn`='0' ");
        $stmt->bindParam(":user_id", $userid, PDO::PARAM_INT);
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function react_max_show($postid)
    {
        $stmt = $this->pdo->prepare("SELECT reactType, count(*) as maxreact from react WHERE reactOn=: postid AND reactCommentOn = '0' AND reactReplyOn = '0' GROUP BY reactType LIMIT 3");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function main_react_count($postid)
    {
        $stmt = $this->pdo->prepare("SELECT count(*) as maxreact from react WHERE reactOn=: postid AND reactCommentOn = '0' AND reactReplyOn = '0'");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>