<?php

namespace App\Tests\Unit\Core\Invoice\Application\Command\CreateInvoice;

use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceHandler;
use App\Core\Invoice\Domain\Exception\InvoiceException;
use App\Core\Invoice\Domain\Exception\InvoiceInactiveUserException;
use App\Core\Invoice\Domain\Exception\InvoiceAmountIsZeroOrLessException;
use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateInvoiceHandlerTest extends TestCase
{
    private UserRepositoryInterface|MockObject $userRepository;

    private InvoiceRepositoryInterface|MockObject $invoiceRepository;

    private CreateInvoiceHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new CreateInvoiceHandler(
            $this->invoiceRepository = $this->createMock(
                InvoiceRepositoryInterface::class
            ),
            $this->userRepository = $this->createMock(
                UserRepositoryInterface::class
            )
        );
    }

    public function test_handle_success(): void
    {
        $email = 'test@test.pl';
        $user = new User($email);
        $user->setIsActive(true);
        $this->userRepository->save($user);

        $invoice = new Invoice(
            $user, 12500
        );

        $this->userRepository->expects(self::once())
            ->method('getByEmail')
            ->willReturn($user);

        $this->invoiceRepository->expects(self::once())
            ->method('save')
            ->with($invoice);

        $this->invoiceRepository->expects(self::once())
            ->method('flush');

        $this->handler->__invoke((new CreateInvoiceCommand($email, 12500)));
    }

    public function test_handle_unsuccess(): void
    {
        $email = 'test@test.pl';
        $user = new User($email);
        $this->userRepository->save($user);

        $this->userRepository->expects(self::once())
            ->method('getByEmail')
            ->willReturn($user);

        $this->expectException(InvoiceInactiveUserException::class);
        $this->expectExceptionMessage('Użytkownik jest nieaktywny. Nie można tworzyć faktury.');

        $this->handler->__invoke((new CreateInvoiceCommand($email, 12500)));
    }

    public function test_handle_user_not_exists(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository->expects(self::once())
            ->method('getByEmail')
            ->willThrowException(new UserNotFoundException());

        $this->handler->__invoke((new CreateInvoiceCommand('test@test.pl', 12500)));
    }

    public function test_handle_invoice_invalid_amount_when_less_than_zero(): void
    {
        $this->expectException(InvoiceAmountIsZeroOrLessException::class);
        $this->expectExceptionMessage('Kwota faktury musi być większa niż zero.');

        $this->handler->__invoke((new CreateInvoiceCommand('test@test.pl', -5)));
    }

    public function test_handle_invoice_invalid_amount_when_zero(): void
    {
        $this->expectException(InvoiceException::class);
        $this->expectExceptionMessage('Kwota faktury musi być większa niż zero.');

        $this->handler->__invoke((new CreateInvoiceCommand('test@test.pl', 0)));
    }
}
