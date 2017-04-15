<?php header ("Content-Type:text/xml"); echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
 
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo base_url();?></loc>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?php echo base_url('posts');?></loc>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo base_url('snapshots');?></loc>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo base_url('openletters');?></loc>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo base_url('tv');?></loc>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo base_url('search');?></loc>
        <priority>0.5</priority>
    </url>
	
    <?php
		foreach($sitemap as $url)
		{
			if(isset($url['categoryID']))
			{
				echo '
					<url>
						<loc>' . base_url('posts/' . $url['categorySlug']) . '</loc>
						<priority>0.5</priority>
					</url>
				';
			}
			elseif(isset($url['postID']))
			{
				echo '
					<url>
						<loc>' . base_url('posts/' . $url['postSlug']) . '</loc>
						<priority>0.5</priority>
					</url>
				';
			}
			elseif(isset($url['snapshotID']))
			{
				echo '
					<url>
						<loc>' . base_url('snapshots/' . $url['snapshotSlug']) . '</loc>
						<priority>0.5</priority>
					</url>
				';
			}
			elseif(isset($url['letterID']))
			{
				echo '
					<url>
						<loc>' . base_url('openletters/' . $url['slug']) . '</loc>
						<priority>0.5</priority>
					</url>
				';
			}
			elseif(isset($url['tvID']))
			{
				echo '
					<url>
						<loc>' . base_url('tv/' . $url['tvSlug']) . '</loc>
						<priority>0.5</priority>
					</url>
				';
			}
			elseif(isset($url['userID']))
			{
				echo '
					<url>
						<loc>' . base_url($url['userName']) . '</loc>
						<priority>0.5</priority>
					</url>
				';
			}
			elseif(isset($url['searchID']))
			{
				echo '
					<url>
						<loc>' . base_url('search/' . format_uri($url['query'])) . '</loc>
						<priority>0.5</priority>
					</url>
				';
			}
		}
	?>
 
</urlset>