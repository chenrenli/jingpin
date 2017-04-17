<?php
namespace app\crawler\controller;

use think\Controller;
use phpQuery;
use app\common\model\Topic;

class Pd extends Controller
{
	public function index()
	{
		$url = "http://www.woshipm.com/category/pmd";

		$data = [];

		$index = 0;

		for ($i = 1; $i < 10 ; $i++) {
			$newUrl = $url;
			if($i > 1){
				$newUrl .= '/page/'.$i;
			}

			$html = phpQuery::newDocumentHTML(my_file_get_contents($newUrl));
			$articles = pq($html)->find('.blockGroup')->find('article');

			foreach ($articles as $article) {
			    $data[$index]['title'] = charsetUtf8(pq($article)->find('h2')->text());
			    $data[$index]['create_at'] = charsetUtf8(pq($article)->find('time')->text());
			    $data[$index]['url'] = pq($article)->find('.stream-list-image a:first')->attr('href');
			    $data[$index]['image'] = pq($article)->find('.stream-list-image img')->attr('src');
			    $data[$index]['category'] = charsetUtf8(pq($article)->find('.stream-list-category a')->text());
			    $data[$index]['description'] = charsetUtf8(pq($article)->find('.stream-list-snipper')->text());
			    $data[$index]['content'] = $this->getContent($data[$index]['url']);
			    $index++;
			}
		}

		$this->saveData($data);
	}

	protected function saveData($datas = [])
	{
		foreach($datas as $data){
			$topic = new Topic();
			$topic->author_id = 1;
			$topic->cate_id = 1;
			$topic->title = $data['title'];
			$topic->content = $data['content'];
			$topic->create_time = strtotime($data['create_at']);
			$topic->update_time = $topic->create_time;
			$topic->save();
		}
	}

	protected function getContent($url)
	{
		$html = phpQuery::newDocumentHTML(my_file_get_contents($url));
		$content = pq($html)->find('.entry-content')->html();
		return charsetUtf8($content);
	}
}