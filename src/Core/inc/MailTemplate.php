<?php

abstract class MailTemplate {

    public abstract function template(string $content, string $headline, string $subline): string;

}