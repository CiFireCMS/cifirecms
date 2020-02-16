<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:admin="http://webns.net/mvcb/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title><?=$feed_title;?></title>
    <link><?=$feed_link;?></link>
    <description><?=$feed_description;?></description>
    <dc:language><?=$feed_language;?></dc:language>
    <dc:rights>Copyright <?=date('Y');?>. <?=get_setting('web_name');?>. All Rights Reserved.</dc:rights>
    <dc:creator><?=$feed_creator;?></dc:creator>
    <dc:rights><?=$feed_copyright;?></dc:rights>

	<admin:generatorAgent rdf:resource="https://www.alweak.com/" />

	<?php foreach ($feed_datas as $res): ?>
    <item>
        <title><?=$res['post_title'];?></title>
        <link><?=post_url($res['post_seotitle']);?></link>
        <guid><?=post_url($res['post_seotitle']);?></guid>
        <description><![CDATA[ <?=html_entity_decode($res['post_content']); ?> ]]></description>
        <enclosure url="<?=post_images($res['post_picture'],'',true);?>" length="49398" type="image/jpeg"/>
        <pubDate><?php echo ci_date($res['datepost'].$res['timepost'], 'D d F Y | h:i A') ?></pubDate>
        <dc:creator><?=$res['user_name'];?></dc:creator>
    </item>
	<?php endforeach ?>
</channel>
</rss>