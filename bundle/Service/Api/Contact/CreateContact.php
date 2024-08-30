<?php

namespace AlmaviaCX\IbexaBrevo\Service\Api\Contact;

use AlmaviaCX\IbexaBrevo\Exception\BrevoException;
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
    /**
     *@throws BrevoException
     */
    public function __invoke(array $data, ?Configuration $configuration = null): CreateUpdateContactModel
    {
        $data['listIds'] = array_unique((array)($data['listIds']??[]));
        foreach ($data['listIds'] as $key=> $listId) {
            if (!is_numeric($listId) || ((int) $listId) <= 0) {
                unset($data['listIds'][$key]);
                continue;
            }
            $data['listIds'][$key] = (int) $listId;
        }
        $this->validate($data);
        $config = $configuration?? $this->getConfiguration();
        $apiInstance = new ContactsApi( config: $config);
        try {
            //$obj = (object)[ 'PRENOM' => 'Ousmane ', 'NOM' => 'KANTE'];
            $createContact = new CreateContactModel($data);

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
            //it may be simple warning (when contact exists and cannot be created) or a critical error
            $this->logger->error(
                'Create contact Error',
                [
                    'data' => $data,
                    'exception' => $e->getMessage(),
                ]
            );
            if ($e instanceof CreateContactException) {
                throw $e;
            }
            throw new CreateContactException($e->getMessage(), $e->getCode(), $e);
        }
    }
}