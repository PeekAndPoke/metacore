<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 11.05.17
 * Time: 17:22
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel\Docs;

use PeekAndPoke\Component\MetaCore\DomainModel\Docs\Doc;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class DocTest extends TestCase
{
    public function testConstruction()
    {
        $subject = new Doc('Summary', 'Description');

        self::assertSame(
            'Summary',
            $subject->getSummary(),
            'The summary must be set correctly'
        );

        self::assertSame(
            'Description',
            $subject->getDescription(),
            'The description must be set correctly'
        );
    }
}
