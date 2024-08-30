<?php

namespace AlmaviaCX\IbexaBrevo\Service\Api\Contact;

use AlmaviaCX\IbexaBrevo\Exception\CreateContactException;
use AlmaviaCX\IbexaBrevo\Service\Api\BrevoApi;
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\ApiException;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateContact as CreateContactModel;
use Brevo\Client\Model\CreateUpdateContactModel;
use Symfony\Contracts\Service\Attribute\Required;
use Throwable;

class CreateContact extends BrevoApi
{
    #[Required]
    public function configureRequiredFields(): void
    {
        parent::setRequiredFields([
            'email',
            'listIds',
            'attributes'
        ]);
    }
    public function __invoke(array $data, ?Configuration $configuration = null): CreateUpdateContactModel
    {
        $this->validate($data);
        $config = $configuration?? $this->getConfiguration();
        $apiInstance = new ContactsApi( config: $config);
        try {
            //$obj = (object)[ 'PRENOM' => 'Ousmane ', 'NOM' => 'KANTE'];
            $createContact = new CreateContactModel([
                'email' => $data['email'],
                'updateEnabled' => (bool)($data['updateEnabled']?? true),
                'attributes' => (object)$data['attributes'],
                'listIds' =>(array)$data['listIds']
            ]);

            $result = $apiInstance->createContact($createContact);
            if ($result === null) {
                throw new CreateContactException(
                    message: 'Could not create contact or contact already Check on brevo side if your contact exist',
                    data: $data
                );
            }
            $this->logger->info(
                'Create contact successfully',
                [
                    'data' => $data,
                    'id' => $result->getId(),
                ]
            );
            return $result;
        } catch (ApiException|Throwable $e) {
            if ($e instanceof CreateContactException) {
                throw $e;
            }
            throw new CreateContactException($e->getMessage(), $e->getCode(), $e);
        }
    }
}