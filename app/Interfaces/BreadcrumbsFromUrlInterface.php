<?php


namespace App\Interfaces;


/**
 * Interface BreadcrumbsFromUrlInterface
 *
 * @return array<int, array{
 *     text: string,
 *     url: string,
 *     is_last: bool
 * }>
 */
interface BreadcrumbsFromUrlInterface
{
    /**
     * @return array<int, array{text: string, url: string, is_last: bool}>
     */
    public function generate(): array;
}
