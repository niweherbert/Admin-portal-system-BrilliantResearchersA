use PHPUnit\Framework\TestCase;

<?php

class SubmitLeavePermissionTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->conn = $this->createMock(mysqli::class);
    }

    public function testFormSubmissionWithValidData()
    {
        $_SESSION['user_id'] = 1;
        $_POST = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'request_type' => 'Sick Leave',
            'reason' => 'Not feeling well'
        ];
        $_FILES = [];

        ob_start();
        include 'submit_leave_permission.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Request submitted successfully.', $output);
    }

    public function testFormSubmissionWithFileUpload()
    {
        $_SESSION['user_id'] = 1;
        $_POST = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'request_type' => 'Sick Leave',
            'reason' => 'Not feeling well'
        ];
        $_FILES = [
            'attachment' => [
                'name' => 'testfile.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/phpYzdqkD',
                'error' => 0,
                'size' => 123
            ]
        ];

        ob_start();
        include 'submit_leave_permission.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Request submitted successfully.', $output);
    }

    public function testFormSubmissionWithMissingFields()
    {
        $_SESSION['user_id'] = 1;
        $_POST = [
            'first_name' => '',
            'last_name' => '',
            'request_type' => '',
            'reason' => ''
        ];
        $_FILES = [];

        ob_start();
        include 'submit_leave_permission.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Error submitting request:', $output);
    }

    public function testFormSubmissionWithInvalidFileUpload()
    {
        $_SESSION['user_id'] = 1;
        $_POST = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'request_type' => 'Sick Leave',
            'reason' => 'Not feeling well'
        ];
        $_FILES = [
            'attachment' => [
                'name' => 'testfile.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/phpYzdqkD',
                'error' => 1,
                'size' => 123
            ]
        ];

        ob_start();
        include 'submit_leave_permission.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Failed to upload file.', $output);
    }

    public function testApproveLeaveRequest()
    {
        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->expects($this->once())
             ->method('execute')
             ->willReturn(true);

        $this->conn->expects($this->once())
                   ->method('prepare')
                   ->willReturn($stmt);

        $_POST = [
            'action' => 'approve',
            'leave_id' => 1
        ];

        ob_start();
        include 'leave_permission.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Leave request approved.', $output);
    }

    public function testRejectLeaveRequest()
    {
        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->expects($this->once())
             ->method('execute')
             ->willReturn(true);

        $this->conn->expects($this->once())
                   ->method('prepare')
                   ->willReturn($stmt);

        $_POST = [
            'action' => 'reject',
            'leave_id' => 1
        ];

        ob_start();
        include 'leave_permission.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Leave request rejected.', $output);
    }
}
?>