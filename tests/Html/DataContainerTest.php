<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Html;

use PHPUnit\Framework\TestCase;
use Torr\Rad\Html\DataContainer;

class DataContainerTest extends TestCase
{
	/**
	 *
	 */
	public function testRenderHtml () : void
	{
		$dataContainer = new DataContainer();

		self::assertSame(
			'<script class="_data-container example" type="application/json">{"&lt;o&gt;":"hai"}</script>',
			$dataContainer->renderToHtml(["<o>" => "hai"], "example")
		);
	}


	/**
	 *
	 */
	public function testRenderHtmlWithId () : void
	{
		$dataContainer = new DataContainer();

		self::assertSame(
			'<script id="example-id" class="_data-container example" type="application/json">{"&lt;o&gt;":"hai"}</script>',
			$dataContainer->renderToHtml(["<o>" => "hai"], "example", "example-id")
		);
	}


	/**
	 *
	 */
	public function testRenderResponse () : void
	{
		$dataContainer = new DataContainer();
		$result = $dataContainer->createResponse(["<o>" => "hai"], "example", "example-id");

		self::assertSame(
			'<script id="example-id" class="_data-container example" type="application/json">{"&lt;o&gt;":"hai"}</script>',
			$result->getContent()
		);
	}
}
