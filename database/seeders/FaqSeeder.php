<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'category' => 'services',
                'question' => 'What areas does FIX currently cover in Bangladesh?',
                'answer' => 'We provide full on-demand service and 24/7 emergency dispatch across Dhaka Metro (Gulshan, Banani, Dhanmondi, Uttara, Mirpur, Motijheel, Mohakhali, etc.), Savar, Gazipur, and Narayanganj. For corporate AMCs, we also cover Chattogram EPZ and other key industrial zones.',
                'order' => 1,
            ],
            [
                'category' => 'safety',
                'question' => 'How are service providers and technicians verified?',
                'answer' => 'Every technician and vendor undergoes strict multi-step vetting: National Identity (NID) check, police background verification, technical competency testing, and trade license audits. All technicians wear standard FIX safety gear and PPE.',
                'order' => 2,
            ],
            [
                'category' => 'amc',
                'question' => 'What is an Annual Maintenance Contract (AMC) and how does it help?',
                'answer' => 'An AMC with FIX is a comprehensive agreement where our engineering team handles regular preventive inspections (HVAC, electrical, plumbing, generators, fire systems) and provides guaranteed emergency response, lowering long-term repair costs by 35-50%.',
                'order' => 3,
            ],
            [
                'category' => 'services',
                'question' => 'What is the emergency turnaround time for urgent breakdowns?',
                'answer' => 'For urgent electrical failures, major plumbing bursts, or HVAC shut-offs across Dhaka, our rapid emergency teams deploy within 45 to 90 minutes. Corporate AMC clients receive priority dedicated response under strict SLA.',
                'order' => 4,
            ],
            [
                'category' => 'billing',
                'question' => 'How does billing and pricing work? Are there hidden charges?',
                'answer' => 'FIX follows 100% transparent pricing. After diagnosing the scope, our engineers provide an itemized upfront digital quotation before any technical work begins. You receive a verified digital invoice and service completion report.',
                'order' => 5,
            ],
            [
                'category' => 'billing',
                'question' => 'Can I apply to become an enlisted service provider or partner?',
                'answer' => 'Yes! We are continuously expanding our partner network. Registered contractors, HVAC specialists, plumbing companies, and electricians can apply via our Become a Partner portal to undergo verification and receive institutional work orders.',
                'order' => 6,
            ],
            [
                'category' => 'amc',
                'question' => 'Do you offer tailored facility maintenance plans for commercial towers?',
                'answer' => 'Yes. Our senior engineers conduct complimentary on-site audits of your commercial building, evaluating generator capacities, HVAC tonnage, substation health, and elevator systems to engineer a tailored SLA maintenance agreement.',
                'order' => 7,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
