import os
import re
import datetime
import unicodedata
from pathlib import Path

import markdown

base_path = Path(__file__).resolve().parent
dir_path = base_path / "글"
output_path = base_path / "import_100_posts.xml"
files = [f for f in os.listdir(dir_path) if f.endswith('.md')]

post_pattern = re.compile(r'^#{1,2}\s*글\s*\d+\.\s*(.+?)$', re.MULTILINE)

posts = []

def get_start_number(filename):
    normalized = unicodedata.normalize('NFC', filename)
    match = re.search(r'글(\d+)', normalized)
    if match:
        return int(match.group(1))
    return 0

files.sort(key=get_start_number)

for filename in files:
    filepath = dir_path / filename
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    matches = list(post_pattern.finditer(content))
    for i, match in enumerate(matches):
        title = match.group(1).strip()
        start = match.end()
        end = matches[i+1].start() if i + 1 < len(matches) else len(content)
        body_md = content[start:end].strip()
        
        body_md = re.sub(r'^\*다음 글.*\n?', '', body_md, flags=re.MULTILINE)
        body_md = re.sub(r'^\*이전 글.*\n?', '', body_md, flags=re.MULTILINE)
        body_md = re.sub(r'^#\s*글\s*\d+~\d+\s*\(.*?\)\s*\n?', '', body_md, flags=re.MULTILINE)
        
        # extensions that are good for wordpress
        body_html = markdown.markdown(body_md, extensions=['tables', 'fenced_code', 'nl2br'])
        posts.append({
            'title': title,
            'content': body_html
        })

wxr_template = """<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/1.2/"
>
<channel>
	<title>중흥복싱블로그</title>
	<link>http://blog.광주복싱.com</link>
	<description>광주 중흥복싱클럽 블로그</description>
	<pubDate>Mon, 23 Apr 2026 09:00:00 +0000</pubDate>
	<language>ko-KR</language>
	<wp:wxr_version>1.2</wp:wxr_version>
	<wp:base_site_url>http://blog.광주복싱.com</wp:base_site_url>
	<wp:base_blog_url>http://blog.광주복싱.com</wp:base_blog_url>
"""

wxr_post_template = """
    <item>
        <title><![CDATA[{title}]]></title>
        <link>http://blog.광주복싱.com/?p={id}</link>
        <pubDate>{pubDate}</pubDate>
        <dc:creator><![CDATA[hong]]></dc:creator>
        <description></description>
        <content:encoded><![CDATA[{content}]]></content:encoded>
        <excerpt:encoded><![CDATA[]]></excerpt:encoded>
        <wp:post_date><![CDATA[{date}]]></wp:post_date>
        <wp:post_date_gmt><![CDATA[{date_gmt}]]></wp:post_date_gmt>
        <wp:comment_status><![CDATA[open]]></wp:comment_status>
        <wp:ping_status><![CDATA[open]]></wp:ping_status>
        <wp:status><![CDATA[publish]]></wp:status>
        <wp:post_parent>0</wp:post_parent>
        <wp:menu_order>0</wp:menu_order>
        <wp:post_type><![CDATA[post]]></wp:post_type>
        <wp:post_password><![CDATA[]]></wp:post_password>
        <wp:is_sticky>0</wp:is_sticky>
        <category domain="category" nicename="jungheung-boxing-story"><![CDATA[중흥복싱클럽 이야기]]></category>
    </item>
"""

base_date = datetime.datetime(2026, 4, 1, 9, 0, 0)

with open(output_path, 'w', encoding='utf-8') as f:
    f.write(wxr_template)
    
    for i, post in enumerate(posts):
        post_id = 1000 + i
        post_date = base_date + datetime.timedelta(hours=i)
        
        f.write(wxr_post_template.format(
            title=post['title'],
            content=post['content'],
            id=post_id,
            date=post_date.strftime("%Y-%m-%d %H:%M:%S"),
            date_gmt=post_date.strftime("%Y-%m-%d %H:%M:%S"),
            pubDate=post_date.strftime("%a, %d %b %Y %H:%M:%S +0000")
        ))
        
    f.write("</channel>\n</rss>")

print(f"Generated import_100_posts.xml with {len(posts)} posts.")
